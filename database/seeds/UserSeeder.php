<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Yogi Sugiar',
            'email' => 'yogi@percik.com',
            'password' => Hash::make('12345678'),
            'level_id' => 2,
            'level_type' => 'Ketua Pembimbing'
        ]);
    }
}
