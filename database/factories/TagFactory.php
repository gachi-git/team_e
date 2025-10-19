<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tag;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition()
    {
        return [
            'label' => $this->faker->word,
            'key' => $this->faker->unique()->word,
            'kind' => 'free',
        ];
    }
}
