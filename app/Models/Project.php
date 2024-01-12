<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public const STATUS_FIXED = 'Fixed';
    public const STATUS_HOURLY = 'Hourly';

    public const STATUS_OPEN = 'Open';
    public const STATUS_CLOSE = 'Close';
    public const STATUS_CANCEL = 'Cancel';

    protected $fillable = [
        'client_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'project_type',
        'milestones_rate',
        'hourly_rate',
        'budget',
        'status'
    ];

    protected $casts = [
        'milestones_rate' => 'json',
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
