<?php

use App\Enums\FeedbackExperience;
use App\Enums\FeedbackStatus;
use App\Jobs\SendFeedbackToWebhook;
use App\Models\Feedback;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

// Submitting feedback

test('given a guest, when visiting the feedback form, then they are redirected to login', function () {
    $response = $this->get(route('feedback.index'));

    $response->assertRedirect(route('login'));
});

test('given an authenticated user, when visiting the feedback form, then only rostered controllers are listed', function () {
    $user = User::factory()->create();
    $rostered = User::factory()->create(['rostered' => true]);
    $unrostered = User::factory()->create(['rostered' => false]);

    $response = $this->actingAs($user)->get(route('feedback.index'));

    $response->assertOk();
    $response->assertSee($rostered->name_reversed);
    $response->assertDontSee($unrostered->name_reversed);
});

test('given an authenticated user, when submitting valid feedback, then it is stored as pending', function () {
    $user = User::factory()->create();
    $controller = User::factory()->create(['rostered' => true]);

    $response = $this->actingAs($user)->post(route('feedback.store'), [
        'controller_id' => $controller->id,
        'position' => 'JAX_CTR',
        'experience' => FeedbackExperience::OUTSTANDING->value,
        'staff_followup' => '1',
        'comments' => 'Great session, very helpful controller.',
    ]);

    $response->assertRedirect(route('feedback.index'));
    $response->assertSessionHas('success');

    $feedback = Feedback::sole();
    expect($feedback->user_id)->toBe($user->id)
        ->and($feedback->controller_id)->toBe($controller->id)
        ->and($feedback->experience)->toBe(FeedbackExperience::OUTSTANDING)
        ->and($feedback->staff_followup)->toBeTrue()
        ->and($feedback->fresh()->status)->toBe(FeedbackStatus::PENDING);
});

test('given an authenticated user, when submitting feedback for a non-rostered controller, then a controller error is returned and nothing is stored', function () {
    $user = User::factory()->create();
    $controller = User::factory()->create(['rostered' => false]);

    $response = $this->actingAs($user)->post(route('feedback.store'), [
        'controller_id' => $controller->id,
        'position' => 'JAX_CTR',
        'experience' => FeedbackExperience::GOOD->value,
        'comments' => 'Some comments.',
    ]);

    $response->assertSessionHasErrors('controller_id');
    expect(Feedback::count())->toBe(0);
});

test('given an authenticated user, when submitting feedback with an invalid experience, then an experience error is returned and nothing is stored', function () {
    $user = User::factory()->create();
    $controller = User::factory()->create(['rostered' => true]);

    $response = $this->actingAs($user)->post(route('feedback.store'), [
        'controller_id' => $controller->id,
        'position' => 'JAX_CTR',
        'experience' => 'Mediocre',
        'comments' => 'Some comments.',
    ]);

    $response->assertSessionHasErrors('experience');
    expect(Feedback::count())->toBe(0);
});

// Managing feedback

test('given an admin, when visiting the feedback management page, then submitted feedback is shown', function () {
    $admin = User::factory()->create();
    $admin->assignRole(['staff', 'admin']);

    $feedback = Feedback::factory()->create(['position' => 'JAX_TWR']);

    $response = $this->actingAs($admin)->get(route('admin.feedback.index'));

    $response->assertOk();
    $response->assertSee('JAX_TWR');
    $response->assertSee($feedback->controller->name);
});

test('given a user without the manage feedback permission, when visiting the feedback management page, then the request is forbidden', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('view dashboard');

    $response = $this->actingAs($user)->get(route('admin.feedback.index'));

    $response->assertForbidden();
});

test('given an admin, when searching feedback, then only matching entries are shown', function () {
    $admin = User::factory()->create();
    $admin->assignRole(['staff', 'admin']);

    $matchingController = User::factory()->create(['first_name' => 'Alice', 'last_name' => 'Zuluair']);
    $otherController = User::factory()->create(['first_name' => 'Bob', 'last_name' => 'Yankeetown']);
    Feedback::factory()->create(['controller_id' => $matchingController->id]);
    Feedback::factory()->create(['controller_id' => $otherController->id]);

    $response = $this->actingAs($admin)->get(route('admin.feedback.index', ['search' => 'Zuluair']));

    $response->assertOk();
    $response->assertSee('Zuluair');
    $response->assertDontSee('Yankeetown');
});

test('given an admin, when stashing pending feedback, then its status becomes stashed', function () {
    $admin = User::factory()->create();
    $admin->assignRole(['staff', 'admin']);

    $feedback = Feedback::factory()->create();

    $response = $this->actingAs($admin)->put(route('admin.feedback.stash', $feedback));

    $response->assertRedirect(route('admin.feedback.index'));
    expect($feedback->fresh()->status)->toBe(FeedbackStatus::STASHED);
});

test('given an admin, when unstashing stashed feedback, then its status returns to pending', function () {
    $admin = User::factory()->create();
    $admin->assignRole(['staff', 'admin']);

    $feedback = Feedback::factory()->create(['status' => FeedbackStatus::STASHED]);

    $response = $this->actingAs($admin)->put(route('admin.feedback.unstash', $feedback));

    $response->assertRedirect(route('admin.feedback.index'));
    expect($feedback->fresh()->status)->toBe(FeedbackStatus::PENDING);
});

test('given an admin, when releasing feedback, then its status becomes released and the webhook job is dispatched', function () {
    Queue::fake();

    $admin = User::factory()->create();
    $admin->assignRole(['staff', 'admin']);

    $feedback = Feedback::factory()->create();

    $response = $this->actingAs($admin)->put(route('admin.feedback.release', $feedback));

    $response->assertRedirect(route('admin.feedback.index'));
    expect($feedback->fresh()->status)->toBe(FeedbackStatus::RELEASED);
    Queue::assertPushed(SendFeedbackToWebhook::class, fn ($job) => $job->feedback->is($feedback));
});

test('given a non-admin user, when stashing feedback, then the request is forbidden and the status is unchanged', function () {
    $user = User::factory()->create();
    $user->assignRole('rostered');

    $feedback = Feedback::factory()->create();

    $response = $this->actingAs($user)->put(route('admin.feedback.stash', $feedback));

    $response->assertForbidden();
    expect($feedback->fresh()->status)->toBe(FeedbackStatus::PENDING);
});

// Staff comments

test('given an admin, when viewing feedback, then its staff comments are shown', function () {
    $admin = User::factory()->create();
    $admin->assignRole(['staff', 'admin']);

    $feedback = Feedback::factory()->create();
    $feedback->staffComments()->create([
        'user_id' => $admin->id,
        'comment' => 'Discussed with the training team.',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.feedback.show', $feedback));

    $response->assertOk();
    $response->assertSee('Discussed with the training team.');
    $response->assertSee($admin->name);
});

test('given an admin, when posting a staff comment, then it is stored against the feedback and their user', function () {
    $admin = User::factory()->create();
    $admin->assignRole(['staff', 'admin']);

    $feedback = Feedback::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.feedback.comments.store', $feedback), [
        'comment' => 'Following up with the controller.',
    ]);

    $response->assertRedirect(route('admin.feedback.show', $feedback));
    $response->assertSessionHas('success');

    $comment = $feedback->staffComments()->sole();
    expect($comment->user_id)->toBe($admin->id)
        ->and($comment->comment)->toBe('Following up with the controller.');
});

test('given an admin, when posting an empty staff comment, then a comment error is returned and nothing is stored', function () {
    $admin = User::factory()->create();
    $admin->assignRole(['staff', 'admin']);

    $feedback = Feedback::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.feedback.comments.store', $feedback), [
        'comment' => '',
    ]);

    $response->assertSessionHasErrors('comment');
    expect($feedback->staffComments()->count())->toBe(0);
});
