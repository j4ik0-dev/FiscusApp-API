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
        Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        //Relación con el usuario (tu cuenta)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Datos básicos
        $table->string('title'); //titulo 
        $table->decimal('amount', 10, 2); // Ej: 8.00 (Soporta hasta 99 millones)
        $table->text('description')->nullable(); // Opcional para notas
        //lógica de "Tipos" para las Vistas
        // 'fixed' = Spotify/Casa, 'variable' = Luz/Agua, 'ant' = Gasto Hormiga
        $table->enum('type', ['fixed', 'variable', 'ant'])->default('variable');
        //lógica de Frecuencia
        // one_time = Hormiga/Una vez, bi_weekly = Quincenal, etc.
        $table->enum('frequency', ['one_time', 'bi_weekly', 'monthly', 'yearly'])->default('one_time');
        // lógica de Duración
        $table->boolean('is_recurring')->default(false);
        // Si es NULL es infinito. Si tiene fecha, muere ese día
        $table->date('recurrence_ends_at')->nullable(); 
        //Fechas y Estado (Para el Dashboard y Gráficas)
        $table->date('payment_date'); // Cuándo toca pagar
        $table->boolean('is_paid')->default(false); // Para la barra de progreso (Pagado vs Pendiente)
        //Evidencia (Recibos)
        $table->string('receipt_path')->nullable(); // Aquí guardaremos la ruta de la foto
        $table->timestamps(); // create_at, updated_at
        $table->softDeletes(); // Papelera de reciclaje (por si borras por error)
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
