<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Image;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    protected $model  = Image::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'code'=> $this->faker->unique()->lexify('?????'),
            'thumbnail' => $this->faker->imageUrl(150, 150, 'thumbnail'),
            'mobile' => $this->faker->imageUrl(300, 600, 'mobile'),
            'tablet' => $this->faker->imageUrl(600, 800, 'tablet'),
            'desktop'=> $this->faker->imageUrl(1920, 1080, 'desktop'),
        ];
    }
}
