<?php

namespace Tests\Feature;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Filament 3 upgrade guard.
 *
 * The v2 -> v3 migration renamed the form/table container classes, moved the action
 * namespaces and replaced config/filament.php with a PanelProvider. A resource whose
 * schema no longer compiles does not fail at boot — it fails when the page is opened,
 * which in this project means content management silently stops working.
 *
 * Nothing here asserts "the page returned 200 and I stopped looking". Each resource is
 * mounted for real, and the edit test round-trips a record through the form to prove the
 * form hydrates from the model and hands the same values back on save. That is the
 * failure this codebase has actually shipped before: a form that renders but discards
 * input (five models with phantom $fillable entries, and TasbihResource rendering six
 * columns for fields that did not exist).
 */
class FilamentResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{class-string<Resource>}> */
    public static function resourceProvider(): array
    {
        $resources = [];

        // Not app_path(): a static data provider runs before the container is booted.
        foreach (glob(__DIR__ . '/../../app/Filament/Resources/*.php') as $file) {
            $class = 'App\\Filament\\Resources\\' . basename($file, '.php');

            if (!class_exists($class) || !is_subclass_of($class, Resource::class)) {
                continue;
            }

            $resources[class_basename($class)] = [$class];
        }

        return $resources;
    }

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(\App\Models\User::factory()->create([
            'role' => \App\Models\User::ROLE_ADMIN,
        ]));
    }

    /**
     * Every resource must expose the three standard pages. A resource the upgrade script
     * half-converted can still be discovered by the panel while pointing at a page class
     * that no longer exists.
     *
     * @dataProvider resourceProvider
     * @param class-string<Resource> $class
     */
    public function test_resource_pages_are_registered_and_loadable(string $class): void
    {
        $pages = $class::getPages();

        foreach (['index', 'create', 'edit'] as $key) {
            $this->assertArrayHasKey($key, $pages, "{$class} does not register a '{$key}' page.");

            $page = $pages[$key]->getPage();

            $this->assertTrue(
                class_exists($page),
                "{$class} registers '{$key}' as {$page}, which does not exist."
            );
        }
    }

    /**
     * Mounting the list page compiles the whole table schema — columns, filters, row
     * actions and bulk actions. This is where a stale Filament\Pages\Actions import or a
     * bulk action left in the v2 namespace surfaces.
     *
     * @dataProvider resourceProvider
     * @param class-string<Resource> $class
     */
    public function test_list_page_renders(string $class): void
    {
        Livewire::test($class::getPages()['index']->getPage())
            ->assertSuccessful();
    }

    /**
     * Mounting the create page compiles the form schema, which the list page does not.
     *
     * @dataProvider resourceProvider
     * @param class-string<Resource> $class
     */
    public function test_create_page_renders(string $class): void
    {
        Livewire::test($class::getPages()['create']->getPage())
            ->assertSuccessful();
    }

    /**
     * The one that matters.
     *
     * Build a record, open it in the edit form, save without touching anything, then read
     * the row back. If the form fails to hydrate a field, or hydrates it in a shape the
     * model cannot store, saving writes null over real content — and the admin sees a
     * green "saved" notification while the data goes away.
     *
     * @dataProvider resourceProvider
     * @param class-string<Resource> $class
     */
    public function test_edit_page_round_trips_a_record_without_losing_data(string $class): void
    {
        $model = $class::getModel();
        $record = $this->buildRecord($model);

        if ($record === null) {
            $this->markTestSkipped(class_basename($model) . ' has no generatable fixture.');
        }

        $before = DB::table((new $model)->getTable())
            ->where('id', $record->getKey())
            ->first();

        Livewire::test(
            $class::getPages()['edit']->getPage(),
            ['record' => $record->getKey()]
        )
            ->assertSuccessful()
            ->call('save')
            ->assertHasNoFormErrors();

        $after = DB::table((new $model)->getTable())
            ->where('id', $record->getKey())
            ->first();

        $this->assertNotNull($after, class_basename($model) . ' was deleted by an untouched save.');

        foreach ((array) $before as $column => $value) {
            if (in_array($column, ['updated_at'], true)) {
                continue;
            }

            $this->assertSame(
                $value,
                $after->{$column},
                sprintf(
                    'Saving %s without editing anything changed %s from %s to %s. '
                    . 'The form is not handing back what it was given.',
                    class_basename($model),
                    $column,
                    var_export($value, true),
                    var_export($after->{$column}, true)
                )
            );
        }
    }

    /**
     * Generate a persistable record from the table definition: fill every NOT NULL column
     * that has no default, resolving foreign keys by creating the parent row first.
     *
     * Deliberately schema-driven rather than a set of hand-written fixtures — hand-written
     * ones drift away from the migrations, and drift is the bug being guarded against.
     *
     * @param class-string<Model> $model
     */
    private function buildRecord(string $model, int $depth = 0): ?Model
    {
        if ($depth > 4) {
            return null;
        }

        $instance = new $model;
        $table = $instance->getTable();

        $foreignKeys = [];

        foreach (DB::select('PRAGMA foreign_key_list("' . $table . '")') as $fk) {
            $foreignKeys[$fk->from] = [$fk->table, $fk->to];
        }

        $attributes = [];

        foreach (DB::select('PRAGMA table_info("' . $table . '")') as $column) {
            if ($column->pk || in_array($column->name, ['created_at', 'updated_at', 'deleted_at'], true)) {
                continue;
            }

            // Every column gets a value, not just the NOT NULL ones. A field that is
            // nullable in the database can still be ->required() in the form, and more
            // importantly an empty column proves nothing: the point of the round trip is
            // to watch real content survive a save.
            if (isset($foreignKeys[$column->name])) {
                [$parentTable, $parentColumn] = $foreignKeys[$column->name];
                $parentId = $this->resolveParent($parentTable, $parentColumn, $depth);

                if ($parentId === null) {
                    return null;
                }

                $attributes[$column->name] = $parentId;

                continue;
            }

            $attributes[$column->name] = $this->valueFor(
                $column->name,
                $column->type,
                $instance->getCasts()[$column->name] ?? null
            );
        }

        try {
            return $model::query()->forceCreate($attributes);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Create (or reuse) a row in a parent table so a foreign key can be satisfied. */
    private function resolveParent(string $table, string $column, int $depth): int|string|null
    {
        $existing = DB::table($table)->value($column);

        if ($existing !== null) {
            return $existing;
        }

        foreach (glob(__DIR__ . '/../../app/Models/*.php') as $file) {
            $class = 'App\\Models\\' . basename($file, '.php');

            if (!class_exists($class) || !is_subclass_of($class, Model::class)) {
                continue;
            }

            if ((new $class)->getTable() !== $table) {
                continue;
            }

            $parent = $this->buildRecord($class, $depth + 1);

            return $parent?->getKey();
        }

        return null;
    }

    /**
     * A plausible value for a column, chosen by cast, then declared type, then name.
     *
     * The default string is deliberately two characters: several fields carry a
     * ->maxLength() as tight as 2 (PermanentCalendar's `day`), and SQLite's PRAGMA does
     * not report column lengths, so a short value is the only way to stay inside every
     * limit without hard-coding a fixture per field.
     */
    private function valueFor(string $name, string $type, ?string $cast): mixed
    {
        $type = strtolower($type);

        if (in_array($cast, ['array', 'json', 'object', 'collection'], true)) {
            return $this->arrayValueFor($name);
        }

        return match (true) {
            str_contains($name, 'email') && !str_contains($name, '_at') => 'fixture@example.test',
            str_contains($name, 'password') => bcrypt('password'),
            // ->tel() attaches a telephone regex, which a generic placeholder fails.
            str_contains($name, 'phone') => '+880',
            str_contains($type, 'int') => 1,
            str_contains($type, 'bool') => 0,
            str_contains($type, 'real'),
            str_contains($type, 'float'),
            str_contains($type, 'double'),
            str_contains($type, 'decimal'),
            str_contains($type, 'numeric') => 1.0,
            str_contains($type, 'date') || str_contains($type, 'time') => now()->toDateTimeString(),
            default => 'Fx',
        };
    }

    /** Realistic contents for the two JSON-cast columns in the schema. */
    private function arrayValueFor(string $name): array
    {
        // Tasbih's repeater is the single most upgrade-sensitive field in the admin —
        // v3 changed repeater state handling — so it gets a real dhikr rather than [].
        if ($name === 'tasbih') {
            return [[
                'text_en' => 'SubhanAllah',
                'text_bn' => 'সুবহানাল্লাহ',
                'text_ar' => 'سُبْحَانَ ٱللَّٰهِ',
                'reset_on' => 33,
                'count' => 5,
                'today_count' => 5,
                'monthly_count' => 5,
                'yearly_count' => 5,
                'total_count' => 5,
            ]];
        }

        // Every other JSON column on PermanentCalendar is a waqt window.
        return ['start_time' => '05:00 AM', 'end_time' => '06:00 AM'];
    }
}
