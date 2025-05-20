<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
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

        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Use Hash facade for consistency
                'role' => 'admin',
            ],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'User1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'User2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'User3',
                'email' => 'user3@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'User4',
                'email' => 'user4@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'User5',
                'email' => 'user5@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'User6',
                'email' => 'user6@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'User7',
                'email' => 'user7@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'User8',
                'email' => 'user8@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
        ]);

        DB::table('organizations')->insert([
            [
                'name' => 'SM',
            ],
            [
                'name' => 'Robinsons',
            ],
            [
                'name' => 'CLDH',
            ],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Client_1',
                'email' => 'client1@example.com',
                'password' => Hash::make('password'),
                'organization_id' => '1',
                'role' => 'client',
            ],
            [
                'name' => 'Client_2',
                'email' => 'client2@example.com',
                'password' => Hash::make('password'),
                'organization_id' => '2',
                'role' => 'client',
            ],
            [
                'name' => 'Client_3',
                'email' => 'client3@example.com',
                'password' => Hash::make('password'),
                'organization_id' => '3',
                'role' => 'client',
            ],
        ]);

        DB::table('job_listing')->insert([
            [
                'title' => 'Retail Sales Associate (Supermarket)',
                'description' => 'Customer service, shelf stocking, and checkout operations in a busy supermarket environment. No experience required - training provided.',
                'organization_id' => 2,
            ],
            [
                'title' => 'Pharmacy Assistant',
                'description' => 'Assist licensed pharmacists with prescription dispensing, inventory management, and customer service in retail pharmacy setting.',
                'organization_id' => 3,
            ],
            [
                'title' => 'Mall Security Officer',
                'description' => 'Patrol premises, monitor surveillance equipment, and ensure safety of visitors and tenants in large shopping complex.',
                'organization_id' => 1,
            ],
            [
                'title' => 'Medical Receptionist',
                'description' => 'Front desk operations for clinic: appointment scheduling, patient registration, and medical records management.',
                'organization_id' => 3,
            ],
            [
                'title' => 'Grocery Store Manager',
                'description' => 'Oversee daily operations of supermarket including staff management, inventory control, and customer satisfaction initiatives.',
                'organization_id' => 2,
            ],
            [
                'title' => 'Optical Retail Associate',
                'description' => 'Assist customers with eyewear selection, perform basic frame adjustments, and maintain optical department inventory.',
                'organization_id' => 3,
            ]
        ]);

    }
}
