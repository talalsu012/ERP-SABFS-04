<?php

namespace Database\Seeders;

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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
		
		 // Create all permissions if they don't exist
    $permissions = [
        'view users', 'create users', 'edit users', 'delete users',
        // Add all your other permissions here
    ];
    
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    // Create super admin role if it doesn't exist
    $role = Role::firstOrCreate(['name' => 'super-admin']);
    $role->syncPermissions(Permission::all());

    // Create super admin user
    $user = User::create([
        'name' => 'Super Admin2',
        'email' => 'superadmin@example.com',
        'password' => bcrypt('sulemanv2'),
        'email_verified_at' => now(),
    ]);

    $user->assignRole('super-admin');
    }
}
