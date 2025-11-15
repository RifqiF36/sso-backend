<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('role_id');
            $table->string('name');
            $table->string('module');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'module']);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('permission_id');
            $table->string('name');
            $table->string('module');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->unique(['name', 'module']);
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
            $table->foreign('role_id')->references('role_id')->on('roles')->cascadeOnDelete();
            $table->foreign('permission_id')->references('permission_id')->on('permissions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};

