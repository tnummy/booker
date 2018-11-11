<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->softDeletes();
            $table->unique('description');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('password');
            $table->integer('user_type_id');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_type_id')->references('id')->on('user_types');
            $table->unique('email');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
        });

        Schema::create('user_dependency_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->softDeletes();
            $table->unique('description');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
        });

        Schema::create('user_dependency_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_dependency_type_id');
            $table->integer('user_id');
            $table->softDeletes();
            $table->foreign('user_dependency_type_id')->references('id')->on('user_dependency_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['user_dependency_type_id', 'user_id']);
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
        });

        Schema::create('user_dependencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('user_id');
            $table->integer('user_dependency_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_dependency_type_id')->references('id')->on('user_dependency_types');
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_types');
    }
}
