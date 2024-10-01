<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('remember_token', 100)->nullable();
        $table->timestamps();
        $table->string('subscription_type')->nullable();
        $table->integer('assessment_no')->default(1);
        $table->integer('assessment_count')->default(0);
        $table->float('descriptive_score')->default(0);
        $table->float('dialogue_score')->default(0);
        $table->float('plot_score')->default(0);
        $table->float('character_score')->default(0);
        $table->float('style_score')->default(0);
        $table->float('pov_score')->default(0);
    });
}

public function down()
{
    Schema::dropIfExists('users');
}

}
