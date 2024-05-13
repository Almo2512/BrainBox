<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Note;
use Illuminate\Support\Facades\Hash;

class UserNoteControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        // Run migrations and seed test data
        $this->artisan('migrate');
        $this->seed();
    }

    public function testIndex()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        //$response = $this->get(route('users.notes.index', ['user' => $user->id]));
        //$response->headers->set('Accept', 'application/json');
        $response = $this->json('GET', route('users.notes.index', ['user' => $user->id]), [], ['Accept' => 'application/json']);
        //var_dump($response);

        $response->assertStatus(200);
    }

  public function testStore()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $data = [
            'description' => $this->faker->sentence,
        ];

        //$response = $this->post(route('users.notes.store', ['user' => $user->id]), $data);
        $response = $this->json('POST', route('users.notes.store', ['user' => $user->id]), $data, ['Accept' => 'application/json']);

        $response->assertStatus(201); 
      
    }
    public function testStoreAndDestroy()
    {
    $user = User::create([
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);
    $data = [
        'description' => $this->faker->sentence,
    ];

    //$response = $this->post(route('users.notes.store', ['user' => $user->id]), $data);
    $response = $this->json('POST', route('users.notes.store', ['user' => $user->id]), $data, ['Accept' => 'application/json']);
    $response->assertStatus(201);

    $note = Note::where('user_id', $user->id)->first();

    $response = $this->delete(route('users.notes.destroy', ['user' => $user->id, 'note' => $note->id]));
    $response->assertStatus(200);

    $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }


}
