<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('days_times', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->json("hours");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('days_times');
    }
};
