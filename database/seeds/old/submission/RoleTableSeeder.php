<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\user;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
    	Role::create(['name' => 'Super Admin']);
    	Role::create(['name' => 'User']);
    	Permission::create(['name' => 'View Record']);


        $user = user::where('email', 'sohail.afzal@barqaab.com')->get()->first();
        if($user !=null){
        $user->assignRole('Super Admin');
    	}

        $user = user::where('email', 'sohail@barqaab.com')->get()->first();
        
        if($user !=null){
        $user->assignRole('User');
        $user->givePermissionTo('View Record');
    	}

    }
}
