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
            Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_facture')->unique(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('montant_total', 12, 2);
            $table->date('date');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
