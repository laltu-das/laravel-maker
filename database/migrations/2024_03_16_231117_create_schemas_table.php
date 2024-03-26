<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('sqlite')->create('schemas', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->json('fields')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('sqlite')->dropIfExists('schemas');
    }
};
