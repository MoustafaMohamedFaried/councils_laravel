<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(PositionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(HeadquarterSeeder::class);
        $this->call(FacultySeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(UserSeeder::class);
        // $this->call(FacultyHeadquarterSeeder::class);
        $this->call(FacultyCouncilSeeder::class);
        $this->call(DepartmentCouncilSeeder::class);
    }
}
