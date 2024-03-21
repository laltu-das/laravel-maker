<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('schema_fields', function (Blueprint $table) {
            $table->id();
            $table->string('fieldName');
            $table->string('dataType');
            $table->string('validation')->nullable();
            $table->string('searchable')->nullable();
            $table->string('fillable')->nullable();
            $table->string('nullable')->nullable();
            $table->string('relationType')->nullable();
            $table->string('foreignModel')->nullable();
            $table->string('foreignKey')->nullable();
            $table->string('localKey')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schema_fields');
    }
};
