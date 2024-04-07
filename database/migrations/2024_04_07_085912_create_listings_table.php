<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('address');
            $table->unsignedSmallInteger('sqft')->default(0);
            $table->unsignedSmallInteger('wifi_speed')->default(0);
            $table->unsignedSmallInteger('max_person')->default(0);
            $table->unsignedSmallInteger('price_per_day')->default(0);
            $table->longText('attachments')->nullable();
            $table->unsignedSmallInteger('full_support_avaiable')->default(0);
            $table->unsignedSmallInteger('gyn_area_avaiable')->default(0);
            $table->unsignedSmallInteger('mini_cafe_avaiable')->default(0);
            $table->unsignedSmallInteger('cinema_avaiable')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
