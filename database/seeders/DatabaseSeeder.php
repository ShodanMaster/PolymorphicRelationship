<?php

namespace Database\Seeders;

use App\Models\TextBlog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $blogs = [];

        for ($i = 1; $i <= 100; $i++) {
            $blogs[] = [
                'title' => "Text Blog #$i",
                'content' => "This is the content of text blog number $i.",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        TextBlog::insert($blogs);

    }
}
