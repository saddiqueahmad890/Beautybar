<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\services\UserLogServices;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use Loggable;
    protected $fillable = [
        'company_id',
        'campaign_name',
        'email_template_id',
        'message',
        'schedule_time',
        'contact_type',
        'status',
    ];



}
