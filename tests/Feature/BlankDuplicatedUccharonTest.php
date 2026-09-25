<?php

namespace Tests\Feature;

use App\Models\Ayat;
use App\Models\Sura;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The command has one job that matters: blank the duplicates without touching rows where
 * bangla_text differs from meaning. On production that is 66 verses which exist nowhere
 * else, so a blanket update would be unrecoverable — whether or not they turn out to be
 * correct, destroying them removes the chance to review them.
 */
class BlankDuplicatedUccharonTest extends TestCase
{
    use RefreshDatabase;

    private function ayat(int $id, string $banglaText, string $meaning): void
    {
        // Both tables carry several NOT NULL columns with no default, so every one has
        // to be supplied even though this test only cares about bangla_text.
        Sura::query()->firstOrCreate(['id' => 1], [
            'name' => 'Al-Fatihah',
            'arabic_name' => 'الفاتحة',
            'english_name' => 'The Opening',
            'bangla_text' => 'আল-ফাতিহা',
            'meaning' => 'সূচনা',
            'status' => 'active',
        ]);

        Ayat::query()->forceCreate([
            'id' => $id,
            'sura_id' => 1,
            'ayat_no' => $id,
            'arabic_text' => 'بِسْمِ',
            'english_text' => 'bismi',
            'bangla_text' => $banglaText,
            'meaning' => $meaning,
            'reference' => '',
            'notes' => '',
            'status' => 'active',
        ]);
    }

    public function test_it_blanks_rows_where_bangla_text_repeats_the_meaning(): void
    {
        $this->ayat(1, 'আল্লাহর নামে', 'আল্লাহর নামে');

        $this->artisan('ayats:blank-duplicated-uccharon')->assertSuccessful();

        $this->assertSame('', Ayat::find(1)->bangla_text);
    }

    /**
     * The one that protects the 66 rescued verses.
     */
    public function test_it_leaves_genuine_uccharon_alone(): void
    {
        $this->ayat(1, 'বিসমিল্লাহির রাহমানির রাহীম', 'আল্লাহর নামে যিনি পরম করুণাময়');

        $this->artisan('ayats:blank-duplicated-uccharon')->assertSuccessful();

        $this->assertSame(
            'বিসমিল্লাহির রাহমানির রাহীম',
            Ayat::find(1)->bangla_text,
            'Genuine uccharon was destroyed — this data exists nowhere else.'
        );
    }

    public function test_it_handles_a_mixed_table(): void
    {
        $this->ayat(1, 'বিসমিল্লাহির রাহমানির রাহীম', 'আল্লাহর নামে');  // genuine
        $this->ayat(2, 'একই লেখা', 'একই লেখা');                          // duplicated
        $this->ayat(3, 'আলিফ লাম মীম', 'আলিফ লাম মীম');                  // duplicated

        $this->artisan('ayats:blank-duplicated-uccharon')->assertSuccessful();

        $this->assertSame('বিসমিল্লাহির রাহমানির রাহীম', Ayat::find(1)->bangla_text);
        $this->assertSame('', Ayat::find(2)->bangla_text);
        $this->assertSame('', Ayat::find(3)->bangla_text);
    }

    public function test_dry_run_changes_nothing(): void
    {
        $this->ayat(1, 'একই লেখা', 'একই লেখা');

        $this->artisan('ayats:blank-duplicated-uccharon', ['--dry-run' => true])
            ->assertSuccessful();

        $this->assertSame('একই লেখা', Ayat::find(1)->bangla_text);
    }

    public function test_running_it_twice_is_safe(): void
    {
        $this->ayat(1, 'বিসমিল্লাহির রাহমানির রাহীম', 'আল্লাহর নামে');
        $this->ayat(2, 'একই লেখা', 'একই লেখা');

        $this->artisan('ayats:blank-duplicated-uccharon')->assertSuccessful();
        $this->artisan('ayats:blank-duplicated-uccharon')->assertSuccessful();

        $this->assertSame('বিসমিল্লাহির রাহমানির রাহীম', Ayat::find(1)->bangla_text);
        $this->assertSame('', Ayat::find(2)->bangla_text);
    }
}
