<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('schema_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schema_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('field_name');
            $table->string('data_type');
            $table->string('fillable')->nullable();
            $table->string('guarded')->nullable();
            $table->string('nullable')->nullable();
            $table->string('unique')->nullable();
            $table->string('relation_type')->nullable();
            $table->string('foreign_model')->nullable();
            $table->string('foreign_key')->nullable();
            $table->string('local_key')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schema_fields');
    }
};
