<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = new Customer();
        $customer->id = "MIFTAH";
        $customer->name = "Miftah Fadilah";
        $customer->email = "miftahfadilah71@gmail.com";
        $customer->save();
    }
}