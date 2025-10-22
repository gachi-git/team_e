<?php
namespace Database\Seeders;

use App\Models\Tag;
use App\Models\University;
use Illuminate\Database\Seeder;

class TagFromUniversitiesSeeder extends Seeder
{
    public function run(): void
    {
        foreach (University::cursor() as $u) {
            $label = $u->name;
            $key = self::key($label);
            Tag::firstOrCreate(
                ['key' => $key, 'kind' => 'university'],
                ['label' => $label]
            );
        }
    }
    private static function key(string $s): string {
        $s = trim($s);
        $s = mb_convert_kana($s, 'asKV', 'UTF-8');
        $s = preg_replace('/[\s　,、，．。]+/u', '', $s) ?? '';
        return mb_strtolower($s, 'UTF-8') ?: md5($s);
    }
}
