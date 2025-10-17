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
            'national_univ.csv',
            'private_univ.csv',
            'public_univ.csv'
        ];

        foreach ($csvFiles as $csvFile) {
            $filePath = base_path('univ/' . $csvFile);
            
            if (($handle = fopen($filePath, 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    if (!empty($data[0])) {
                        University::create([
                            'name' => $data[0]
                        ]);
                    }
                }
                fclose($handle);
            }
        }
    }
}
