<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    // Estos son los campos que permitimos guardar desde la App
    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'description',
        'type',             // fixed, variable, ant
        'frequency',        // monthly, one_time
        'is_recurring',
        'recurrence_ends_at',
        'payment_date',
        'is_paid',
        'receipt_path'
    ];

    // Esto convierte los datos automáticamente al formato correcto
    protected $casts = [
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'is_paid' => 'boolean',
        'recurrence_ends_at' => 'date',
        'payment_date' => 'date',
    ];

    // Relación: Un gasto pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
