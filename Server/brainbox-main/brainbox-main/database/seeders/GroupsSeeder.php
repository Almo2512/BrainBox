<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = new User([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => bcrypt('root'),
            ]);
            $user->save();
            $users[] = $user;
        }

        // Erstelle einige Gruppen
      
        for ($i = 1; $i <= 5; $i++) {
            $group = new Group([
                'title' => "Group $i",
            ]);
            $group->save();
            $groups[] = $group;

            // Weise Benutzer den Gruppen zu
            $groupUsers = array_slice($users, ($i - 1) * 3, 3);
            foreach ($groupUsers as $groupUser) {
                $group->users()->attach($groupUser->id, ['is_admin' => false]);
            }
        }
    }
}
