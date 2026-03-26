<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiaryActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apiary_activities', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('apiary_id')->constrained('apiaries')->onDelete('cascade');
            $table->string('user_nickname', 100)->nullable();
            $table->string('role', 20);
            $table->string('activity', 255);
            $table->timestamps();

            $table->foreign('user_nickname')->references('nickname')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apiary_activities');
    }
}
