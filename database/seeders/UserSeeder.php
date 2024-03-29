<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $is_exist_user = User::withTrashed()->where('email', 'admin@example.com')->first();
        $last_user = User::select('id')->orderBy('created_at', 'desc')->first();
        if(!isset($is_exist_user->id)){
            if($last_user){
                $id = $last_user->id;
            }else{
                $id = 0;
            }
            $user = [
                'id'=>(int)$id +1,
                'first_name'=>'Superadmin',
                'last_name'=>'Super',
                'middle_name'=>'Admin',
                'gender'=>1,
                'email'=>'admin@example.com',
                'is_admin'=>1,
                'password'=>Hash::make('12345678'),
            ];
            User::create($user);
        }else{
            if(!isset($is_exist_user->deleted_at)){
                echo "Current user is exist status deleted";
            }else{
                echo "Current user is exist status active";
            }
        }
    }
}
