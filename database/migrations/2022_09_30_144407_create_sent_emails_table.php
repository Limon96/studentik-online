<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            $table->string('to')->default('')->nullable();
            $table->string('subject')->default('')->nullable();
            $table->longText('body')->nullable();
            $table->integer('status')->default('0')->nullable();
            $table->string('token')->default('')->nullable();
            $table->string('unsubscribe_token')->default('')->nullable();
            $table->dateTime('viewed_at')->nullable();
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
        Schema::dropIfExists('sent_emails');
    }
};
