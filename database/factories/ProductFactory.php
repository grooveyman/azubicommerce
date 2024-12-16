<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

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
            'name'=> $this->faker->word,
            'category'=> $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }

    public function withImages(){
        return $this->has(Image::factory()->count(4), 'images');
    }
}
