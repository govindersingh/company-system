<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public const STATUS_UPWORK = 'Upwork';
    public const STATUS_FIVER = 'Fiver';
    public const STATUS_SLACK = 'Slack';
    public const STATUS_WHATSAPP = 'WhatsApp';
    public const STATUS_SKYPE = 'Skype';
    public const STATUS_OTHER = 'Other';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'platform',
        'description'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }
}
