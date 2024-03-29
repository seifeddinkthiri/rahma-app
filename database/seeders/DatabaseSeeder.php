<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $account = Account::create(['name' => 'Rahma Corporation']);

        User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin',
            'owner' => true,
            'admin' => true,
            'wait' => false,
        ]);

        User::factory(5)->create(['account_id' => $account->id]);


    }
}
