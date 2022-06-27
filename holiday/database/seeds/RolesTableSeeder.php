<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $role = $this->role('id', 1);
        if (!$role->exists) 
        {
            \DB::table('roles')->insert(array (
                0 => 
                array (
                    'id' => 1,
                    'name' => 'admin',
                    'display_name' => 'Administrator',
                    'created_at' => '2018-12-21 10:25:07',
                    'updated_at' => '2018-12-21 10:25:07',
                ),
                1 => 
                array (
                    'id' => 2,
                    'name' => 'customer',
                    'display_name' => 'Customer (non-admin)',
                    'created_at' => '2018-12-21 10:25:07',
                    'updated_at' => '2019-05-18 06:27:26',
                ),
                2 => 
                array (
                    'id' => 3,
                    'name' => 'organiser',
                    'display_name' => 'Organiser (Semi-admin)',
                    'created_at' => '2018-12-24 08:55:32',
                    'updated_at' => '2019-05-18 06:27:45',
                ),
            ));
        }    
        
        
    }

    protected function role($field, $for)
    {
        return Role::firstOrNew([$field => $for]);
    }
}