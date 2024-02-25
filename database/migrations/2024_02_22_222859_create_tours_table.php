<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('travelId')->references('id')->on('travels');
            $table->string('name');
            $table->date('startingDate')->index();
            $table->date('endingDate');
            $table->unsignedMediumInteger('price')->index();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
