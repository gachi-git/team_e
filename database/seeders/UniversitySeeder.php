<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universities = [
            // 旧帝大7校
            ['name' => '東京大学', 'name_kana' => 'とうきょうだいがく', 'type' => '国立'],
            ['name' => '京都大学', 'name_kana' => 'きょうとだいがく', 'type' => '国立'],
            ['name' => '大阪大学', 'name_kana' => 'おおさかだいがく', 'type' => '国立'],
            ['name' => '東北大学', 'name_kana' => 'とうほくだいがく', 'type' => '国立'],
            ['name' => '名古屋大学', 'name_kana' => 'なごやだいがく', 'type' => '国立'],
            ['name' => '九州大学', 'name_kana' => 'きゅうしゅうだいがく', 'type' => '国立'],
            ['name' => '北海道大学', 'name_kana' => 'ほっかいどうだいがく', 'type' => '国立'],
            
            // 早慶上理
            ['name' => '早稲田大学', 'name_kana' => 'わせだだいがく', 'type' => '私立'],
            ['name' => '慶應義塾大学', 'name_kana' => 'けいおうぎじゅくだいがく', 'type' => '私立'],
            ['name' => '上智大学', 'name_kana' => 'じょうちだいがく', 'type' => '私立'],
            ['name' => '東京理科大学', 'name_kana' => 'とうきょうりかだいがく', 'type' => '私立'],
            
            // MARCH
            ['name' => '明治大学', 'name_kana' => 'めいじだいがく', 'type' => '私立'],
            ['name' => '青山学院大学', 'name_kana' => 'あおやまがくいんだいがく', 'type' => '私立'],
            ['name' => '立教大学', 'name_kana' => 'りっきょうだいがく', 'type' => '私立'],
            ['name' => '中央大学', 'name_kana' => 'ちゅうおうだいがく', 'type' => '私立'],
            ['name' => '法政大学', 'name_kana' => 'ほうせいだいがく', 'type' => '私立'],
            
            // 関関同立
            ['name' => '関西大学', 'name_kana' => 'かんさいだいがく', 'type' => '私立'],
            ['name' => '関西学院大学', 'name_kana' => 'かんせいがくいんだいがく', 'type' => '私立'],
            ['name' => '同志社大学', 'name_kana' => 'どうししゃだいがく', 'type' => '私立'],
            ['name' => '立命館大学', 'name_kana' => 'りつめいかんだいがく', 'type' => '私立'],
        ];

        foreach ($universities as $university) {
            University::create($university);
        }
    }
}
