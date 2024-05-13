<?php


namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void{

        parent::setUp();

        $this->artisan('migrate');
        $this->seed();

    }

    public function testStore()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(8),
        ];

        //$response = $this->post(route('users.store'), $userData);
        $response = $this->json('POST', route('users.store'), $userData, ['Accept' => 'application/json']);
        $response->assertStatus(201); ;
    }

}


