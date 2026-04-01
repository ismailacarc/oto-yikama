<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();              // ppf, seramik, kimyasal, diger
            $table->string('unit')->default('adet');             // adet, m², kg, litre, ml
            $table->decimal('stock_quantity', 10, 3)->default(0);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('min_stock_alert', 10, 3)->default(0); // düşük stok uyarısı
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};
