<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->integer('current_price')->default(null);
            $table->boolean('declined')->default(0);
            $table->boolean('confirmed')->default(0);
            $table->boolean('responded')->default(0);
            $table->timestamp('event_date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
        });

        Schema::create('negotiation_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->integer('offer_price');
            $table->string('message');
            $table->boolean('dismissed')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
        });

        Schema::create('booking_dependencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id');
            $table->integer('user_dependency_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('user_dependency_id')->references('id')->on('user_dependencies');
            $table->unique(['booking_id', 'user_dependency_id']);
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('negotiation_history');
        Schema::dropIfExists('bookings');
    }
}
