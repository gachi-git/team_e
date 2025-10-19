<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicMasterSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1) 大学
            $universities = [
                ['name' => '九州大学', 'slug' => 'kyushu'],
                ['name' => '東京大学', 'slug' => 'utokyo'],
                ['name' => '京都大学', 'slug' => 'kyoto'],
            ];

            foreach ($universities as $u) {
                DB::table('universities')->updateOrInsert(
                    ['slug' => $u['slug']],
                    ['name' => $u['name'], 'slug' => $u['slug'], 'updated_at' => now(), 'created_at' => now()]
                );
            }

            // slug => id のマップを作成（IDのズレ対策）
            $uniId = DB::table('universities')->pluck('id', 'slug'); // ['kyushu'=>1, 'utokyo'=>2, ...]

            // 2) サークル（university_slug で指定し、IDに解決）
            $circles = [
                ['name' => '登山サークル', 'slug' => 'alpine',     'university_slug' => 'kyushu'],
                ['name' => '写真部',     'slug' => 'photo',      'university_slug' => 'kyushu'],
                ['name' => '軽音部',     'slug' => 'lightmusic', 'university_slug' => 'utokyo'],
                // 大学非所属のサークルを許容したいなら 'university_slug' を省略 or null にしてOK（下記参照）
            ];

            foreach ($circles as $c) {
                $univId = isset($c['university_slug']) ? ($uniId[$c['university_slug']] ?? null) : null;

                DB::table('circles')->updateOrInsert(
                    ['slug' => $c['slug']],
                    [
                        'name' => $c['name'],
                        'slug' => $c['slug'],
                        // 大学必須設計なら $univId が null の場合はスキップ/例外にする
                        'university_id' => $univId,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        });
    }
}
