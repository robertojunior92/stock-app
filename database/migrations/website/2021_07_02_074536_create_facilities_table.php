<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->text('description')->nullable();
            $table->string('icon')->default('fas fa-map-marker');
            $table->string('icon_image')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active')->default(1);
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->bigInteger('position')->default(0);

            $table->string('meta_name')->nullable();
            $table->string('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
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
        Schema::dropIfExists('facilities');
    }
}
