<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    public const STATUS_UNPAID = 'Unpaid';
    public const STATUS_PAID = 'Paid';
    public const STATUS_OVERDUE = 'Overdue';

    protected $fillable = [
        'project_id',
        'amount',
        'date',
        'status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
