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
        $csvFiles = [
            base_path('univ/national_univ.csv') => '国立',
            base_path('univ/public_univ.csv') => '公立',
            base_path('univ/private_univ.csv') => '私立',
        ];

        foreach ($csvFiles as $filePath => $type) {
            if (file_exists($filePath)) {
                $this->seedFromCsv($filePath, $type);
            }
        }
    }

    private function seedFromCsv(string $filePath, string $type): void
    {
        $file = fopen($filePath, 'r');
        
        if ($file !== false) {
            while (($data = fgetcsv($file)) !== false) {
                if (count($data) >= 2) {
                    $name = trim($data[0]);
                    $nameKana = trim($data[1]);
                    
                    // BOM文字を削除
                    $name = preg_replace('/^\xEF\xBB\xBF/', '', $name);
                    $nameKana = preg_replace('/^\xEF\xBB\xBF/', '', $nameKana);
                    
                    if (!empty($name) && !empty($nameKana)) {
                        University::create([
                            'name' => $name,
                            'name_kana' => $nameKana,
                            'type' => $type,
                        ]);
                    }
                }
            }
            fclose($file);
        }
    }
}
