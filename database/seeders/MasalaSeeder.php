<?php

namespace Database\Seeders;

use App\Models\Masala;
use App\Models\MasalaCategory;
use Illuminate\Database\Seeder;

/**
 * Seeds the standard chapters of fiqh as categories, and keeps the two example masalas
 * that already existed.
 *
 * Only the category headings are seeded — they are structural, not rulings. Actual
 * masa-el content is entered through the Filament admin, since fiqh rulings should come
 * from a source the project owner trusts rather than being invented here.
 */
class MasalaSeeder extends Seeder
{
    /** slug => [English, Bangla, Arabic, sort order] */
    private const CATEGORIES = [
        'taharat'  => ['Purification', 'পবিত্রতা', 'الطهارة', 1],
        'salat'    => ['Prayer', 'নামায', 'الصلاة', 2],
        'sawm'     => ['Fasting', 'রোযা', 'الصوم', 3],
        'zakat'    => ['Zakat', 'যাকাত', 'الزكاة', 4],
        'hajj'     => ['Hajj', 'হজ্জ', 'الحج', 5],
        'janazah'  => ['Funeral rites', 'জানাযা', 'الجنازة', 6],
        'muamalat' => ['Transactions', 'লেনদেন', 'المعاملات', 7],
        'family'   => ['Family', 'পারিবারিক', 'الأسرة', 8],
        'misc'     => ['Miscellaneous', 'বিবিধ', 'متفرقات', 9],
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $slug => [$en, $bn, $ar, $order]) {
            MasalaCategory::updateOrCreate(
                ['slug' => $slug],
                ['name_en' => $en, 'name_bn' => $bn, 'name_ar' => $ar,
                 'sort_order' => $order, 'status' => true]
            );
        }

        $sawm = MasalaCategory::where('slug', 'sawm')->first();

        $examples = [
            [
                'question'  => 'Intention for Fasting',
                'answer'    => 'The intention for obligatory fasting (like Ramadan) must be made before dawn.',
                'reference' => 'Fatawa Alamgiri',
            ],
            [
                'question'  => 'Eating Forgetfully',
                'answer'    => 'If a person eats or drinks forgetfully, their fast is not broken.',
                'reference' => 'Hedaya',
            ],
        ];

        foreach ($examples as $index => $example) {
            Masala::updateOrCreate(
                ['question' => $example['question']],
                $example + [
                    'masala_category_id' => $sawm?->id,
                    'sort_order' => $index + 1,
                    'status' => true,
                ]
            );
        }
    }
}
