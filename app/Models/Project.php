<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public const STATUS_PLANNED = 'Planned';
    public const STATUS_IN_PROGRESS = 'In progress';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_CANCELLED = 'Cancelled';

    protected $fillable = [
        'client_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'budget',
        'status',
        'details'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function billings()
    {
        return $this->hasMany(Billing::class, 'project_id');
    }

    public function getAmountSumAttribute()
    {
        return $this->billings()->sum('amount');
    }
}
