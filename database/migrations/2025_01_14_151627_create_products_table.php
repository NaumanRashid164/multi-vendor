<?php

use App\Models\User;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("title")->unique();
            $table->string("slug")->unique();
            $table->longText("description");
            $table->foreignId("department_id")->index()->constrained();
            $table->foreignId("category_id")->index()->constrained();
            $table->decimal("price", 10, 2);
            $table->string("status");
            $table->integer("quantity");
            $table->foreignIdFor(User::class, "created_by")->index()->constrained();
            $table->foreignIdFor(User::class, "updated_by")->index()->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
