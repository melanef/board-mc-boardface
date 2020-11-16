<?php

use App\Users\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'name' => 'Amauri',
            'email' => 'amaurimelojunior@gmail.com',
            'password' => Hash::make('amjsenha'),
        ]);

        factory(User::class, 10)->create();
    }
}
