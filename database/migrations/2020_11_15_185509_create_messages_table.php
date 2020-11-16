<?php

use App\Messages\Models\Message;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text(Message::COLUMN_SUBJECT)->nullable(false);
            $table->text(Message::COLUMN_CONTENT)->nullable(false);
            $table->integer(Message::COLUMN_CREATED_BY)->nullable(false)->unsigned();
            $table->dateTime(Message::COLUMN_START_DATE)->nullable(false);
            $table->dateTime(Message::COLUMN_EXPIRATION_DATE)->nullable(true);
            $table->boolean(Message::COLUMN_DELETED)->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
