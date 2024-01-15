<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasbih', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate();
            $table->json('subhanallah')->default(json_encode(['text_en' => 'Subhanallah', 'text_bn' => 'সুবহানআল্লাহ', 'text_ar' => 'سبحان الله', 'reset_on' => 33, 'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]));
            $table->json('alhamdulillah')->default(json_encode(['text_en' => 'Alhamdulillah', 'text_bn' => 'আলহামদুলিল্লাহ', 'text_ar' => 'الحمد لله', 'reset_on' => 33,'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]));
            $table->json('allahuakbar')->default(json_encode(['text_en' => 'Allahuakbar', 'text_bn' => 'আল্লাহু আকবর', 'text_ar' => 'الله أكبر', 'reset_on' => 33, 'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]));
            $table->json('astagfirullah')->default(json_encode(['text_en' => 'Astagfirullah', 'text_bn' => 'আস্তাগফিরুল্লাহ', 'text_ar' => 'أستغفر الله', 'reset_on' => 0,'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]));
            $table->json('lailahaillallah')->default(json_encode(['text_en' => 'La-ila-ha illallah', 'text_bn' => 'লা ইলাহা ইল্লাল্লাহ', 'text_ar' => 'لا إله إلا الله', 'reset_on' => 0, 'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]));
            $table->json('subhanallahiwalhamdulillahi')->default(json_encode(['text_en' => 'Subhanallahi Wabihamdihi Wa Subhanallahili Azeem', 'text_bn' => 'সুবহানআল্লাহি ও বিহামদিহি, সুবহানআল্লাহি লা ইলাহা ইল্লাল্লাহু আল আজিম', 'text_ar' => 'سبحان الله وبحمده، سبحان الله العظيم', 'reset_on' => 0, 'today_count' => 0, 'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => 0]));

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasbih');
    }
};
