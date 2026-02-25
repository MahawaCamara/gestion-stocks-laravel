<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
         $table->id();
         $table->string('designation');
         $table->foreignId('categorie_id')->constrained()->onDelete('cascade');
         $table->string('description');
         $table->decimal('prix_vente', 10, 2);
         $table->integer('stock_actuel')->default(0);
         $table->integer('seuil_alerte')->default(5);
         $table->timestamps();
   });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
