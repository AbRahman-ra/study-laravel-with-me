<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\table;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::create([
      'name' => 'rashed',
      'email' => 'rashed@gmail.com',
      'password' => Hash::make('rashed'),
      'phone' => '+97300000000'
    ]);

    DB::table('users')->insert([
      'name' => 'admin',
      'email' => 'admin@gmail.com',
      'password' => Hash::make('admin'),
    ]);
  }
}
