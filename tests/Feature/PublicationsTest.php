<?php

use App\Models\Publication;
use App\Models\PublicationCategory;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

function makeCategory(): PublicationCategory
{
    return PublicationCategory::create([
        'title'         => 'Standard Operating Procedures',
        'description'   => 'Facility SOPs',
        'display_order' => 0,
    ]);
}

function makePublication(string $filePath = 'documents/example.pdf'): Publication
{
    return Publication::create([
        'publication_category_id' => makeCategory()->id,
        'name'                    => 'Example Document',
        'description'             => 'An example document.',
        'version'                 => '1.0',
        'file_path'               => $filePath,
        'original_filename'       => 'example.pdf',
        'file_size'               => 1234,
    ]);
}

// --- Public file serving (the 403 fix) ---

test('the public file route serves a stored document without authentication', function () {
    Storage::fake('public');
    Storage::disk('public')->put('documents/example.pdf', 'PDF-CONTENTS');

    $publication = makePublication();

    $response = $this->get(route('publications.file', $publication));

    $response->assertStatus(200);
    expect($response->streamedContent())->toBe('PDF-CONTENTS');
});

test('the public file route returns 404 when the physical file is missing', function () {
    Storage::fake('public');

    $publication = makePublication('documents/missing.pdf');

    $this->get(route('publications.file', $publication))->assertStatus(404);
});

test('file_url points at the serving route instead of the storage symlink', function () {
    $publication = makePublication();

    expect($publication->file_url)->toBe(route('publications.file', $publication));
});

// --- Admin permission (documents:write) ---

test('a user without documents:write cannot access publication management', function () {
    $user = User::factory()->create();
    $user->assignRole('staff'); // has "view dashboard" but not "documents:write"

    $this->actingAs($user)
        ->get(route('admin.publications.index'))
        ->assertStatus(403);
});

test('a facilities user with documents:write can access publication management', function () {
    $user = User::factory()->create();
    $user->assignRole('facilities', 'staff'); // mirrors real roster assignment

    $this->actingAs($user)
        ->get(route('admin.publications.index'))
        ->assertStatus(200);
});

// --- Upload validation (allowed types) ---

test('an allowed file type can be uploaded', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $user->assignRole('facilities', 'staff');
    $category = makeCategory();

    $response = $this->actingAs($user)->post(route('admin.publications.store'), [
        'publication_category_id' => $category->id,
        'name'                    => 'New SOP',
        'description'             => 'A new SOP.',
        'version'                 => '2.0',
        'file'                    => UploadedFile::fake()->create('sop.pdf', 200, 'application/pdf'),
    ]);

    $response->assertRedirect(route('admin.publications.index'));
    expect(Publication::where('name', 'New SOP')->exists())->toBeTrue();
});

test('a disallowed file type is rejected', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $user->assignRole('facilities', 'staff');
    $category = makeCategory();

    $response = $this->actingAs($user)->post(route('admin.publications.store'), [
        'publication_category_id' => $category->id,
        'name'                    => 'Bad Upload',
        'description'             => 'Should be rejected.',
        'version'                 => '1.0',
        'file'                    => UploadedFile::fake()->create('notes.txt', 10, 'text/plain'),
    ]);

    $response->assertSessionHasErrors('file');
    expect(Publication::where('name', 'Bad Upload')->exists())->toBeFalse();
});
