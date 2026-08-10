<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Guard against a bug class this codebase has hit five separate times: a model's
 * $fillable naming columns that do not exist.
 *
 * Eloquent silently drops unknown keys during mass assignment, so the write appears
 * to succeed and the data is discarded. Found in Tasbih, PermanentCalendar, Doa,
 * Category and RamazanSchedule — in Doa's case (`english_tex`) it meant an admin
 * editing a dua's English translation had the change thrown away on save.
 */
class ModelFillableTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{class-string<Model>}> */
    public static function modelProvider(): array
    {
        $models = [];

        // Not app_path(): a static data provider runs before the container is booted.
        foreach (glob(__DIR__ . '/../../app/Models/*.php') as $file) {
            $class = 'App\\Models\\' . basename($file, '.php');

            if (!class_exists($class) || !is_subclass_of($class, Model::class)) {
                continue;
            }

            $models[class_basename($class)] = [$class];
        }

        return $models;
    }

    /**
     * @dataProvider modelProvider
     * @param class-string<Model> $class
     */
    public function test_every_fillable_attribute_is_a_real_column(string $class): void
    {
        $model = new $class;
        $table = $model->getTable();

        $this->assertTrue(
            Schema::hasTable($table),
            "{$class} declares table '{$table}', which does not exist."
        );

        $columns = Schema::getColumnListing($table);
        $phantom = array_diff($model->getFillable(), $columns);

        $this->assertSame(
            [],
            array_values($phantom),
            sprintf(
                "%s::\$fillable names %s, which %s not exist on '%s'. " .
                "Eloquent drops unknown keys silently, so writes to %s would be discarded.",
                class_basename($class),
                implode(', ', $phantom),
                count($phantom) === 1 ? 'does' : 'do',
                $table,
                count($phantom) === 1 ? 'it' : 'them'
            )
        );
    }

    /**
     * A model with no $fillable and no $guarded override cannot be mass-assigned at all,
     * which is the same silent-data-loss failure from the other direction.
     *
     * @dataProvider modelProvider
     * @param class-string<Model> $class
     */
    public function test_models_are_mass_assignable(string $class): void
    {
        $model = new $class;

        $this->assertFalse(
            $model->getFillable() === [] && $model->getGuarded() === ['*'],
            class_basename($class) . ' declares neither $fillable nor $guarded, '
                . 'so every mass assignment against it is silently discarded.'
        );
    }
}
