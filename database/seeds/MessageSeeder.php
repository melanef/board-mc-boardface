<?php

use App\Messages\Models\Message;
use Faker\Factory;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * @var Faker
     */
    private $faker;

    /**
     * MessageSeeder constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->insert([
            Message::FIELD_SUBJECT => 'First message',
            Message::COLUMN_CONTENT => $this->faker->text(),
            Message::COLUMN_CREATED_BY => 1,
            Message::COLUMN_START_DATE => new DateTime('2020-11-15 11:00:00'),
            Message::COLUMN_EXPIRATION_DATE => new DateTime('2020-12-31 23:59:59'),
        ]);
    }
}
