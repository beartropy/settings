<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('beartropy_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index()->default('general');
            $table->string('key')->index();
            $table->text('value')->nullable();
            
            // Metadata for UI generation
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->default('string'); // text, boolean, select, number, textarea
            $table->json('options')->nullable(); // For select/radio types
            
            $table->boolean('is_system')->default(false); // If true, cannot be deleted
            $table->timestamps();

            $table->unique(['group', 'key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('beartropy_settings');
    }
};
