# Discrepancies & Known Issues

This is a follow-up work queue produced during the repository documentation run. Every item was found while reading the code to write the docs; **nothing here has been fixed** (the documentation run was read-only on code). The one exception is the certification ER diagram, which was corrected because it *is* documentation — see [§10](#10-documentation-corrected-in-this-run).

Each entry lists **where** it is, **why it matters**, and a **suggested fix**. Severity is a rough triage aid, not a formal assessment. Verify each against current code before acting — line-level details may have shifted.

Ordering: [Security](#1-security) · [Project identity & licensing](#2-project-identity--licensing) · [Test & CI configuration](#3-test--ci-configuration) · [Deployment](#4-deployment) · [Confirmed code bugs](#5-confirmed-code-bugs) · [Schema, model & migration mismatches](#6-schema-model--migration-mismatches) · [Dead / leftover code](#7-dead--leftover-code) · [Config & environment gaps](#8-config--environment-gaps) · [Minor / cosmetic](#9-minor--cosmetic) · [Docs corrected in this run](#10-documentation-corrected-in-this-run)

---

## 1. Security

### 1.1 — `.env.staging` holds real-looking secrets but is NOT tracked (no repo exposure)
- **Where:** `.env.staging` exists on disk locally and contains what look like real secrets — a database password, VATUSA API key, VATSIM client secret, a mail (ZeptoMail) SMTP password, and two Discord webhook URLs.
- **Status:** The file is **not** tracked by git and never appears in history (`git log --all -- .env.staging` is empty). It is already covered by the `.env.*` rule in `.gitignore`, so it cannot be committed accidentally. There is therefore **no repository exposure** of these credentials.
- **Suggested action:** No git remediation is required — keep the `.gitignore` rule in place. Rotation is not forced by any repo leak; rotate only if these values were exposed through some other channel. Continue to keep all `.env*` files except `.env.example` out of version control, and store staging/production config on the deployment host or a secret manager.

### 1.2 — Hardcoded personal data in the dev-only `/test-email` route
- **Where:** `routes/web.php` (dev/local-only route group).
- **Why it matters:** `/test-email` hardcodes a personal email address plus fixed record IDs (`User::find(...)`, `TrainingAssignment::find(1)`). Not a secret, but brittle and it leaks a personal address into the repo.
- **Suggested fix:** Parameterize via query string or config, or remove the route.

---

## 2. Project identity & licensing

### 2.1 — License mismatch (GPLv3 vs MIT)
- **Where:** `LICENSE`, `README.md`, `CONTRIBUTING.md` all say **GPLv3**; `composer.json` declares `"license": "MIT"`.
- **Why it matters:** The declared license is legally ambiguous when two sources conflict.
- **Suggested fix:** Decide the intended license and make `composer.json` agree with `LICENSE`.

### 2.2 — `composer.json` still carries starter-kit identity
- **Where:** `composer.json` — `"name": "laravel/blank-livewire-starter-kit"` and the generic starter description.
- **Suggested fix:** Rename to the project (e.g. `zjx-artcc/site-laravel`) and write a real description.

---

## 3. Test & CI configuration

### 3.1 — Test database engine conflict (pgsql vs SQLite)
- **Where:** `phpunit.xml` sets `DB_CONNECTION=pgsql` + `DB_DATABASE=zjx_test`; `tests/docker-compose.yml` sets `DB_CONNECTION=sqlite`; `.github/workflows/test.yml` provisions a SQLite file.
- **Why it matters:** It only works in CI because the container's real environment variable overrides `phpunit.xml`'s `<env>` entry. Running `php artisan test` **locally** (no docker env) falls back to `phpunit.xml` → pgsql `zjx_test`. So local and CI exercise *different* database engines, and DB-specific behavior is not tested identically.
- **Suggested fix:** Pick one engine for tests and make `phpunit.xml`, `tests/docker-compose.yml`, and CI agree. (This behavior is documented as-is in [`database.md`](database.md) and [`deployment.md`](deployment.md).)

### 3.2 — `.env.test` sets `APP_ENV=production`
- **Where:** `.env.test` vs `phpunit.xml` (`APP_ENV=testing`).
- **Why it matters:** Confusing and risky — a test run that somehow picks up `.env.test` would believe it is production.
- **Suggested fix:** Set `.env.test` to `APP_ENV=testing`.

---

## 4. Deployment

### 4.1 — Both staging AND production deploy on push to `main`
- **Where:** `.github/workflows/build-and-push-staging.yml` and `build-and-push-production.yml` both trigger on `push` to `main`.
- **Why it matters:** A single merge to `main` deploys to production with no separate release gate (tag, manual approval, or environment protection beyond whatever the GitHub `production` Environment enforces).
- **Suggested fix:** Gate production on a tag/release or a required manual approval (GitHub Environment protection rule), and keep only staging on push-to-`main`.

### 4.2 — Pint lint is check-only
- **Where:** `.github/workflows/lint.yml` — the auto-commit step is commented out.
- **Why it matters:** Style is neither auto-fixed nor enforced as a failing check.
- **Suggested fix:** Either run `pint --test` so the job fails on violations, or re-enable auto-commit. (Informational.)

---

## 5. Confirmed code bugs

These are functional defects found while documenting. Grouped by system.

### Training
- **5.1 `User::trainingTicketsAsInstructor()` returns the wrong model.** `app/Models/User.php` — it returns `hasMany(TrainingAssignment::class, 'instructor_id')`, duplicating `trainingAssignmentsAsInstructor()`. It should almost certainly return `TrainingTicket::class`. Any caller expecting an instructor's training *tickets* gets *assignments* instead.
- **5.2 `deactivate training assignments` permission is never seeded.** `app/Http/Controllers/Training/TrainingAssignmentController.php` (`destroy`/drop path) checks `hasPermissionTo('deactivate training assignments')`, but that permission is absent from `database/seeders/PermissionSeeder.php`. Under Spatie, checking an unknown permission throws `PermissionDoesNotExist`, so the staff drop path is broken. **Fix:** seed the permission (and grant it to the right roles) or change the check to an existing permission.
- **5.3 `TrainingAssignment` status: cast vs mutator conflict.** `status` is cast to the int-backed `TrainingStatus` enum, but a `status()` attribute mutator also lowercases incoming values, and `destroy()` writes the raw string `'withdrawn'`, which is not a valid `TrainingStatus` case. **Fix:** write a valid enum case; reconcile the cast and mutator.
- **5.4 `solo-certs.update` route has no controller method.** The solo-cert resource route includes `update`, but `SoloCertController` defines only index/show/create/store/destroy (and `show()` is an empty stub). Hitting `solo-certs.update` errors. **Fix:** implement or drop the route.
- **5.5 `SoloCert::toSearchableArray()` reads a nonexistent property.** It references `$this->issued_by` (and `is_null($this->issued_by)`), but the column/relation is `issued_by_id` / `issuedBy()`. The issuer is always null in the search index. **Fix:** use `issued_by_id` / the relation.

### Events
- **5.6 `EventPositionAssignmentController::store()` is unrouted and buggy.** No route references it (`routes/web.php`), and it validates `position_id` but then reads `$data['position_name']` (never validated), so it would throw even if wired. Consequently there is **no working "staff assigns a position" path** — `assigned_position`/`position_status` are never written beyond the DB default. **Fix:** decide whether staff assignment is a feature; if so, implement and route it correctly; if not, delete the controller.
- **5.7 `EventPositionPresetController` redirects to a nonexistent route name.** `store()`/`update()`/`destroy()` redirect to `position-presets.index`, but the resource is named `admin.events.position-presets`, so the real name is `admin.events.position-presets.index`. These redirects throw `RouteNotFoundException`. **Fix:** use the fully-qualified route name.
- **5.8 `EventFieldController` only implements `index()` but is a full resource.** `event-fields` is registered as `Route::resource`, so create/store/edit/update/destroy hit missing methods. **Fix:** restrict the resource to `->only(['index'])` or implement the actions.
- **5.9 `EventRegistration::store()` has duplicate array keys.** The `EventPosition::create([...])` call lists `requested_position`, `start`, `end`, `notes` twice; PHP keeps the last occurrence, so the *validated* values are silently discarded in favor of the raw component properties. **Fix:** remove the duplicate keys and keep the validated values.

### Visiting controllers
- **5.10 `visit.update` route has no controller method.** `PUT /admin/visit-requests/{visitRequest}` → `VisitFacilityController@update`, but there is no `update()` method → 500. **Fix:** implement or remove the route.
- **5.11 `manage.blade.php` branches on nonexistent `$request->approved`.** `VisitorRequest` has no `approved` column/cast/accessor (status is the `VisitRequestStatus` enum), so the expression is always falsy and the list never shows Approved/Denied states. **Fix:** branch on `status`.

### Certifications
- **5.12 `UserCertification` references a nonexistent FK column.** `app/Models/UserCertification.php` `$fillable` and `certificationLevel()` use `facility_certification_level_id`, but the migration column (and composite-PK member) is `certification_level_id`. The relationship cannot resolve. **Fix:** rename the model references to `certification_level_id`.
- **5.13 Certification display on the roster is broken.** `resources/views/roster/index.blade.php` uses `$user->certifications->where('facility_id', …)` (no `facility_id` on `UserCertification`) and `$cert->level->identifier` (the relation is `certificationLevel`, not `level`, and `CertificationLevel` has `abbreviation`, not `identifier`). **Fix:** correct the relation/attribute names once 5.12 is resolved.
- **5.14 `certification-level-row` Livewire component does not exist.** `resources/views/livewire/certification-levels-table.blade.php` renders `@livewire('certification-level-row', …)`, but no such class/view exists → render failure. **Fix:** create the component or change the table to not reference it.
- **5.15 No write path for `user_certifications`.** No controller, route, Livewire component, or seeder ever creates/deletes rows in `user_certifications`. The data model exists but user certifications cannot be granted or revoked through the app. **Fix:** build the grant/revoke UI, or document it as an intentionally external/DB-only process.

### Auth
- **5.16 `/login` does not actually 301.** `routes/web.php`: `redirect()->route('auth.redirect', 301)` passes `301` as the *route parameters* argument, not the HTTP status. The result is a normal 302. **Fix:** `redirect()->route('auth.redirect', [], 301)` if a 301 is intended.

---

## 6. Schema, model & migration mismatches

### 6.1 — `FeaturedField::events()` targets the wrong pivot table
- **Where:** `app/Models/FeaturedField.php` uses `event_featured_fields` (plural); the migration creates `event_featured_field` (singular).
- **Why it matters:** The relationship breaks if invoked. Separately, `Event` defines *no* relationship to `FeaturedField` — events store featured fields in the `events.featured_fields` **JSON column** instead, so the `featured_fields` table and `event_featured_field` pivot are effectively orphaned (the table is only read to build a picklist). Featured fields are dual-modeled (table + JSON).
- **Suggested fix:** Decide on one model (JSON vs relational). If keeping the pivot, fix the table name and add the `Event` side; if not, drop the unused table/pivot.

### 6.2 — `TrainingTicket` `$fillable` / activity-log reference nonexistent columns
- **Where:** `app/Models/TrainingTicket.php` — `$fillable` includes `ots_status` and `solo_granted` (no migration creates them); `getActivitylogOptions()` references `session_date` (columns are `session_start`/`session_end`) and `ots_status`.
- **Suggested fix:** Remove the phantom columns or add migrations for them.

### 6.3 — `training_assignments.training_type` default `0` is not a valid enum case
- **Where:** migration default `0`; `TrainingType` starts at `S1 = 1`.
- **Why it matters:** A row left at the default fails to cast to the enum.
- **Suggested fix:** Set a valid default or make the column require an explicit value.

### 6.4 — `User::events()` pivot column name mismatch
- **Where:** `app/Models/User.php` `events()` uses `->withPivot('…','note',…)`; the `event_positions` column is `notes` (plural). Also `EventPosition::events()` declares `belongsToMany(Event)`, which is nonsensical for a row that belongs to one event via `event_id` and appears unused.
- **Suggested fix:** Use `notes`; remove the unused `belongsToMany`.

### 6.5 — Non-nullable FKs declared `onDelete('set null')`
- **Where:** `training_tickets.instructor_id`, `solo_certs.issued_by_id` — columns are not nullable but use `onDelete('set null')`, which cannot apply.
- **Suggested fix:** Make the columns nullable or change the delete behavior.

### 6.6 — `users` primary-key semantics differ from the migration
- **Where:** the migration uses `$table->id()` (auto-increment bigint), but `id` is used as the VATSIM CID and always assigned explicitly on insert. `app/Models/User.php` does not set `$incrementing = false` / `$keyType`.
- **Why it matters:** Works because the CID is fillable and upserts key on it, but the intent isn't expressed and auto-increment could collide conceptually.
- **Suggested fix:** Consider modeling the CID explicitly (`$incrementing = false`) and documenting it.

### 6.7 — `users` migration `down()` drops a table never created
- **Where:** the `users` migration `down()` drops `password_reset_tokens`, which its `up()` never creates. The `User` model also hides `password`/`remember_token`, which have no backing columns (VATSIM OAuth means no local passwords).
- **Suggested fix:** Clean up `down()` and the phantom hidden attributes.

### 6.8 — `Staff` model primary key is not unique
- **Where:** `app/Models/Staff.php` declares `$primaryKey = 'title_short'`, but multiple rows legitimately share a `title_short` (INS, MTR, etc.), and the `staff` migration defines no `id`/unique/primary key. Queries use `where(...)`, so it functions, but PK-based operations are ambiguous.
- **Suggested fix:** Add a real primary key to the `staff` table.

### 6.9 — `OnlineController::user()` uses `hasOne` where `belongsTo` fits
- **Where:** `app/Models/OnlineController.php`. Also `online_controllers.user_id` is a `foreignId` with no constraint (intentional — online controllers may have no local user). Resolves correctly but is the inverse of the conventional relation type.
- **Suggested fix:** Consider `belongsTo` for clarity (low priority).

---

## 7. Dead / leftover code

- **7.1 `ProfileController` is unrouted.** `app/Http/Controllers/ProfileController.php` and its view `resources/views/profile/index.blade.php` are reachable from no route (the live profile pages are served by `UserController`). Candidate for deletion.
- **7.2 `CreateEvent` Livewire component is a stub and unused.** `app/Livewire/CreateEvent.php` has an empty `save()`, hardcoded `featuredFieldsOptions` (`['KMCO','KJAX','KDAB']`), and no Blade references it. Real event creation goes through `EventController::store()`.
- **7.3 `VatusaRole` DTO is empty.** `app/DTOs/VatusaRole.php` contains only a namespace declaration; facility roles are parsed inline in `VatusaFacilityInfoDTO`.
- **7.4 `Welcome` mailable has no production trigger.** `app/Mail/Welcome.php` is only instantiated by the dev-only `/test-email` route; nothing sends it on first login.
- **7.5 `SyncTrainingTickets` is never scheduled.** `routes/console.php` schedules only `SyncRoster` and `UpdateOnlineControllers`. `SyncTrainingTickets` runs only via the dev-only `/sync-training` route, so in production nothing pushes training tickets to VATUSA automatically. Confirm whether that is intended.
- **7.6 `zeptomail` mailer transport has no driver.** `config/mail.php` defines a `zeptomail` mailer with `'transport' => 'zeptomail'`, but no package provides it and nothing calls `Mail::extend('zeptomail', …)`. Setting `MAIL_MAILER=zeptomail` would fail. ZeptoMail is actually reached via the standard `smtp` mailer. Remove the dead entry or wire a real transport.
- **7.7 Inertia SSR leftovers.** `composer.json` has `dev:ssr` (`npm run build:ssr`, `php artisan inertia:start-ssr`), but Inertia is not a dependency and `package.json` has no `build:ssr`. Starter-kit residue.
- **7.8 Volt is effectively unused.** `livewire/volt` is installed and `app/Providers/VoltServiceProvider.php` mounts `resources/views/livewire` and `resources/views/pages` (the latter does not exist), but every component is class-based under `app/Livewire/`. Consider removing Volt if unused.
- **7.9 Commented-out Socialite registration.** `app/Providers/AppServiceProvider.php` has an older `Socialite::extend('vatsim', …)` block commented above the active one. Delete it.
- **7.10 Test placeholders / starter stubs.** `tests/Pest.php` still defines a stub `function something()`; `tests/Unit/ExampleTest.php` and `tests/Feature/ExampleTest.php` are starter placeholders. Remove.
- **7.11 Unused event view.** `resources/views/events/manage.blade.php` appears unused — `EventController::manage()` returns `manage-events.index`.

---

## 8. Config & environment gaps

- **8.1 Env vars read by config but missing from `.env.example`.** `VATSIM_API_URL` (read in `config/app.php`, used by `UpdateOnlineControllers`; only `VATSIM_AUTH_URL` is in the example), `LOG_DISCORD_WEBHOOK_URL` / `LOG_DISCORD_IGNORE_EXCEPTIONS` (read in `config/logging.php`), and `LARAVEL_OPTIMIZE` (consumed by `entrypoint.sh`). A fresh clone lacks these. **Fix:** add them to `.env.example` with safe placeholders.
- **8.2 Scout driver is implicit.** No `config/scout.php` is published and no `SCOUT_DRIVER` appears in any `.env`. The `collection` driver is only the vendor default (`env('SCOUT_DRIVER', 'collection')`). **Fix:** publish `config/scout.php` and/or set `SCOUT_DRIVER` explicitly so the choice is intentional.
- **8.3 Environment-name mismatch for dev conveniences.** `SyncRoster::handle()` grants roles to the "Web" test users only when `App::environment() == 'development'`, while the dev-only routes use `App::environment('development', 'local')`. On a `local` environment the sync convenience won't fire even though `/sync` exists. **Fix:** standardize the environment name(s).

---

## 9. Minor / cosmetic

- **9.1** `roster/index.blade.php` reads `env('VATUSA_FACILITY')` directly in the view — breaks under `php artisan config:cache`. Use `config()`.
- **9.2** Staff views (`staff-card`, training-team/assistant blocks) dereference `$staff->user` without a null guard, even though out-of-division staff can have no local user. `RosterController` carries a `// fix:` comment about this. Latent null-deref.
- **9.3** Visiting checklist `needsBasic` may be semantically inverted in `visit/create.blade.php` (VATUSA `needbasic` mapped to a positive-sounding label). Verify the intended true/false meaning.
- **9.4** `VisitFacilityController::approve()` stores the raw `operatingInitials` but only uppercases the value for its duplicate-check; a lowercase submission could be stored un-normalized. Normalize before storing.
- **9.5** `User::updateFromVatusa()` returns the `upsert()` affected-row count, not a `User` model, despite call sites reading as if a model comes back. Verify callers.
- **9.6** `RosterController` has a typo'd local variable `$certificaionFacilities` (passed correctly to the view; cosmetic).
- **9.7** `Staff::fromFacilityInfoDTO` sets `title_long` to "Training Administrator" for both `TA` and `ATA` (ATA should likely be "Assistant Training Administrator").
- **9.8** `routes/web.php` has a `TODO: make store` on the `training-assignment/create` POST route (a `create` action used for a store operation), and `training-assignments.destroy` takes the id via request payload rather than a route parameter — inconsistent with the other resourceful routes.

---

## 10. Documentation corrected in this run

The previous `docs/certification-system-overview.md` contained an inaccurate ER diagram: singular table names (`certification_level`, `certification_facility`) where the real tables are plural (`certification_levels`, `certification_facilities`), a one-to-one relationship where the schema is many-to-many via `user_certifications` (composite PK `user_id` + `certification_level_id`), and missing `timestamps`. That file was **removed** and replaced by a corrected diagram in [`systems/certifications.md`](systems/certifications.md).
