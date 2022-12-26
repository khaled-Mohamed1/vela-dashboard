<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin Seeder
        $user = User::create([
            'full_name' => 'Vela Vela',
            'email' => 'vela@gmail.com',
            'password' => bcrypt('password'),
            'job' => 'Vela',
            'phone_NO' => '0000000000',
            'company_NO' => '123456',
            'company_name' => 'Vela',
            'role_id' =>1,
            'status' => 1
            ]);

        $role = Role::create(['name' => 'Super Admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
