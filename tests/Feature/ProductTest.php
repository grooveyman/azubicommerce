<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;

class ProductTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    #[Test]
    public function it_can_create_product()
    {


        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Prepare product data
        $productData = [

            'name' => 'Test Product',
            'category' => 'Test Category',
            'price' => 99.99,
            'thumbnail' => 'thumbnail.jpg',
            'mobile' => 'mobile.jpg',
            'tablet' => 'tablet.jpg',
            'desktop' => 'desktop.jpg'
        ];


        // Make a request with the token in the Authorization header
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/products/', $productData);


        // Assert the product and its images were created in the database
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'category' => 'Test Category',
            'price' => 99.99,
        ]);

        // Assert the images were saved
        // Assuming you store the images in a separate table or field
        $product = Product::with('images')->first();
        foreach ($productData as $image) {
            $this->assertTrue($product->images()->exists());
        }

    }
}
