<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('s3cret'),
        ];

        User::create($user);
    }
}
