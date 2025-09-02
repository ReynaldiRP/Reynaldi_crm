<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::factory()->count(2)->create()->each(function ($role) {
            User::factory()->count(5)->create([
                'role_id' => $role->id,
            ]);
        });

        Product::factory()->count(10)->create();
        Customer::factory()->count(10)->create();
        Lead::factory()->count(10)->create();
    }
}
