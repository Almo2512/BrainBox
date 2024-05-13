<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
class GroupsControllerTest extends TestCase
{
     


    public function it_can_store_a_group()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $data = [
            'title' => 'New Group',
        ];

        $response = $this->actingAs($admin)->postJson(route('users.groups.store', ['user' => $admin->id]), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('groups', [
            'title' => 'New Group',
        ]);
        $this->assertDatabaseHas('group_user', [
            'user_id' => $admin->id,
            'group_id' => $response->json('id'), 
            'is_admin' => true,
        ]);
    }
}
