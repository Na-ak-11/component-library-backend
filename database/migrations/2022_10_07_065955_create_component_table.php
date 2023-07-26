<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('component', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category');
            $table->foreignId('user_id')->constrained('users');
            $table->string('name');
            $table->string('discription')->nullable();
            $table->integer('viewes')->nullable();
            $table->integer('likes')->nullable();
            $table->text('code_referance');
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
        Schema::dropIfExists('component');
       
    }
}
