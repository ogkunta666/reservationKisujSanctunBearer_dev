<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    use RefreshDatabase;

    #[Test]
    public function user_can_register()
    {
       // Arrange
       $payload = [
              'name' => "Test User",
              'email' => "testuser@example.com",
              'password' => "password",
              'password_confirmation' => "password"
       ];
         // Act
         $response = $this->postJson('/api/register', $payload);

         //assert
            $response->assertStatus(201)-> assertJsonStructure(['message','user']);
            $this->assertDatabaseHas('users', [
                'email' => 'testuser@example.com'
            ]);
        
    }
}