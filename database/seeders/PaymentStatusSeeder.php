<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = PaymentStatus::first();
        if(!$role){
            $data = [
                'status'=>1
            ];
             PaymentStatus::create($data);
        }else{
            echo "Payment status is exist";
        }
    }
}
