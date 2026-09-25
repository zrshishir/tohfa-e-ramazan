<?php

namespace Tests\Feature;

use App\Models\Ayat;
use App\Models\Sura;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The Basmala split touches 112 of 114 suras, so the tests that matter most are the
 * ones asserting what it must NOT do: sura 1 where the Basmala is genuinely ayat 1,
 * sura 9 which has none, and 27:30 where it sits inside the verse quoting Sulayman's
 * letter.
 */
class SplitBasmalaTest extends TestCase
{
    use RefreshDatabase;

    private const BASMALA = 'بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ';

    private function sura(int $id): void
    {
        if (Sura::find($id)) {
            return;
        }

        // forceCreate, not firstOrCreate: `id` is not in Sura's $fillable, so a normal
        // create silently auto-assigns a different key and the ayat foreign key fails.
        Sura::query()->forceCreate([
            'id' => $id,
            'name' => "Sura {$id}",
            'arabic_name' => 'سورة',
            'english_name' => "Sura {$id}",
            'bangla_text' => 'সূরা',
            'meaning' => 'সূরা',
            'status' => 'active',
        ]);
    }

    private function ayat(int $suraId, int $ayatNo, string $arabic): Ayat
    {
        $this->sura($suraId);

        return Ayat::query()->forceCreate([
            'sura_id' => $suraId,
            'ayat_no' => $ayatNo,
            'arabic_text' => $arabic,
            'english_text' => 'transliteration',
            'bangla_text' => 'উচ্চারণ',
            'meaning' => 'অর্থ',
            'reference' => '',
            'notes' => '',
            'status' => 'active',
        ]);
    }

    private function seedFatihah(): void
    {
        // Sura 1 ayat 1 is the template the command copies the Basmala from.
        $this->ayat(1, 1, self::BASMALA);
    }

    public function test_it_splits_the_basmala_into_its_own_row(): void
    {
        $this->seedFatihah();
        $this->ayat(2, 1, self::BASMALA . ' الٓمٓ');

        $this->artisan('ayats:split-basmala')->assertSuccessful();

        $basmala = Ayat::where('sura_id', 2)->where('ayat_no', 0)->first();
        $this->assertNotNull($basmala, 'No ayat_no=0 row was created.');
        $this->assertSame(self::BASMALA, $basmala->arabic_text);

        $this->assertSame('الٓمٓ', Ayat::where('sura_id', 2)->where('ayat_no', 1)->first()->arabic_text);
    }

    /**
     * Al-Fatihah's Basmala IS ayat 1. Splitting it would invent a verse and leave ayat 1
     * empty.
     */
    public function test_it_leaves_sura_1_alone(): void
    {
        $this->seedFatihah();

        $this->artisan('ayats:split-basmala')->assertSuccessful();

        $this->assertSame(0, Ayat::where('sura_id', 1)->where('ayat_no', 0)->count());
        $this->assertSame(self::BASMALA, Ayat::where('sura_id', 1)->where('ayat_no', 1)->first()->arabic_text);
    }

    /** At-Tawbah has no Basmala at all. */
    public function test_it_leaves_sura_9_alone(): void
    {
        $this->seedFatihah();
        $this->ayat(9, 1, 'بَرَآءَةٌۭ مِّنَ ٱللَّهِ');

        $this->artisan('ayats:split-basmala')->assertSuccessful();

        $this->assertSame(0, Ayat::where('sura_id', 9)->where('ayat_no', 0)->count());
        $this->assertSame('بَرَآءَةٌۭ مِّنَ ٱللَّهِ', Ayat::where('sura_id', 9)->where('ayat_no', 1)->first()->arabic_text);
    }

    /**
     * 27:30 quotes Sulayman's letter, which contains the Basmala mid-verse. Only
     * ayat_no = 1 is ever considered, so this must survive untouched.
     */
    public function test_it_never_touches_the_basmala_inside_27_30(): void
    {
        $this->seedFatihah();
        $verse = 'إِنَّهُۥ مِن سُلَيْمَٰنَ وَإِنَّهُۥ ' . self::BASMALA;
        $this->ayat(27, 30, $verse);

        $this->artisan('ayats:split-basmala')->assertSuccessful();

        $this->assertSame($verse, Ayat::where('sura_id', 27)->where('ayat_no', 30)->first()->arabic_text);
        $this->assertSame(0, Ayat::where('sura_id', 27)->where('ayat_no', 0)->count());
    }

    /** Suras 95 and 97 spell it بِّسْمِ, with an extra shadda. */
    public function test_it_matches_the_shadda_spelling_variant(): void
    {
        $this->seedFatihah();
        $this->ayat(95, 1, 'بِّسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ وَٱلتِّينِ');

        $this->artisan('ayats:split-basmala')->assertSuccessful();

        $this->assertSame(1, Ayat::where('sura_id', 95)->where('ayat_no', 0)->count());
        $this->assertSame('وَٱلتِّينِ', Ayat::where('sura_id', 95)->where('ayat_no', 1)->first()->arabic_text);
    }

    public function test_the_bom_is_stripped(): void
    {
        $this->seedFatihah();
        $this->ayat(2, 1, "\u{FEFF}" . self::BASMALA . ' الٓمٓ');

        $this->artisan('ayats:split-basmala')->assertSuccessful();

        $this->assertStringNotContainsString("\u{FEFF}", Ayat::where('sura_id', 2)->where('ayat_no', 0)->first()->arabic_text);
    }

    public function test_running_it_twice_does_not_duplicate(): void
    {
        $this->seedFatihah();
        $this->ayat(2, 1, self::BASMALA . ' الٓمٓ');

        $this->artisan('ayats:split-basmala')->assertSuccessful();
        $this->artisan('ayats:split-basmala')->assertSuccessful();

        $this->assertSame(1, Ayat::where('sura_id', 2)->where('ayat_no', 0)->count());
        $this->assertSame('الٓمٓ', Ayat::where('sura_id', 2)->where('ayat_no', 1)->first()->arabic_text);
    }

    public function test_dry_run_writes_nothing(): void
    {
        $this->seedFatihah();
        $this->ayat(2, 1, self::BASMALA . ' الٓمٓ');

        $this->artisan('ayats:split-basmala', ['--dry-run' => true])->assertSuccessful();

        $this->assertSame(0, Ayat::where('sura_id', 2)->where('ayat_no', 0)->count());
    }
}
