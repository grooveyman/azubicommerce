<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CartTest extends TestCase
{
  
    #[Test]
    public function it_can_create_cart()
    {
        $cartData = ['quantity' => 1];



        $response = $this->postJson('/api/carts', $cartData);

        $response->assertStatus(200)->assertJson([
            'status' => 200,
            'results' => 'Cart created successfully',
        ]);

        $this->assertDatabaseHas('carts', data: ['quantity' => 1]);

    }
}
