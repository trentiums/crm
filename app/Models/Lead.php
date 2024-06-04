<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'leads';

    protected $dates = [
        'deal_close_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'company_user_id',
        'name',
        'email',
        'phone',
        'company_name',
        'company_size',
        'company_website',
        'lead_status_id',
        'lead_channel_id',
        'lead_conversion_id',
        'budget',
        'time_line',
        'description',
        'deal_amount',
        'win_close_reason',
        'deal_close_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function lead_status()
    {
        return $this->belongsTo(LeadStatus::class, 'lead_status_id');
    }

    public function lead_channel()
    {
        return $this->belongsTo(LeadChannel::class, 'lead_channel_id');
    }

    public function product_services()
    {
        return $this->belongsToMany(ProductService::class);
    }

    public function lead_conversion()
    {
        return $this->belongsTo(LeadConversion::class, 'lead_conversion_id');
    }

    public function getDealCloseDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDealCloseDateAttribute($value)
    {
        $this->attributes['deal_close_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
