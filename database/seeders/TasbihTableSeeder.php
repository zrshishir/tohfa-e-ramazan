<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasbihTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasbihs = [
            ['user_id' => 1, 'subhanallah' => json_encode(['text_en' => 'Subhanallah', 'text_bn' => 'সুবহানআল্লাহ', 'text_ar' => 'سبحان الله', 'reset_on' => 33, 'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]), 'alhamdulillah' => json_encode(['text_en' => 'Alhamdulillah', 'text_bn' => 'আলহামদুলিল্লাহ', 'text_ar' => 'الحمد لله', 'reset_on' => 33,'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]), 'allahuakbar' => json_encode(['text_en' => 'Allahuakbar', 'text_bn' => 'আল্লাহু আকবর', 'text_ar' => 'الله أكبر', 'reset_on' => 33, 'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]), 'astagfirullah' => json_encode(['text_en' => 'Astagfirullah', 'text_bn' => 'আস্তাগফিরুল্লাহ', 'text_ar' => 'أستغفر الله', 'reset_on' => 0,'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]), 'lailahaillallah' => json_encode(['text_en' => 'La-ila-ha illallah', 'text_bn' => 'লা ইলাহা ইল্লাল্লাহ', 'text_ar' => 'لا إله إلا الله', 'reset_on' => 0, 'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]), 'subhanallahiwalhamdulillahi' => json_encode(['text_en' => 'Subhanallahi Wabihamdihi Wa Subhanallahili Azeem', 'text_bn' => 'সুবহানআল্লাহি ও বিহামদিহি, সুবহানআল্লাহি লা ইলাহা ইল্লাল্লাহু আল আজিম', 'text_ar' => 'سبحان الله وبحمده، سبحان الله العظيم', 'reset_on' => 0, 'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0])],
        ];

        DB::table('tasbih')->insert($tasbihs);
    }
}
