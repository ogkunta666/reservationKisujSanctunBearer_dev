<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;

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

    #[Test]
    public function user_can_login()
    {
        //Arrange
        $user = \App\Models\User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => 'password'
        ]);

        $credentials = [
            'email' => 'testuser@example.com',
            'password' => 'password'
        ];

        //Act
        $response = $this->postJson('/api/login', $credentials);
        //Assert
        $response->assertStatus(200)->assertJsonStructure(['access_token','token_type']);
    }

    #[Test]

    public function user_can_logout(){

        // Arrange
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Act
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/logout');

        // Assert
        $response->assertStatus(200)->assertJson(['message' => 'Logged out successfully']);
    }
    
}