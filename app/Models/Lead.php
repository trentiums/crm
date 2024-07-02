<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Lead extends Model implements HasMedia
{
    use SoftDeletes, Auditable, HasFactory, InteractsWithMedia;

    public $table = 'leads';

    protected $appends = [
        'documents',
        'is_editable_deleteable'
    ];

    protected $dates = [
        'deal_close_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'deal_amount' => 'float',
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

    const ORDER_BY = [
        "1" => "created_at",
        "2" => "name",
        "3" => "email",
        "4" => "company_user_id",
    ];

    const ORDER = [
        "1" => "ASC",
        "2" => "DESC",
    ];

    const STATUS_UPDATE_TYPE = [
        "1" => "status",
        "2" => "channel",
        "3" => "conversion",
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

    public function company_user()
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function getDealCloseDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDealCloseDateAttribute($value)
    {
        $this->attributes['deal_close_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getIsEditableDeleteableAttribute()
    {
        $companyUser = CompanyUser::where("user_id", "=", auth()->id())->first();

        if ($companyUser) {
            if ($this->company_user_id == $companyUser->id || (auth()->user()->user_role == array_flip(Role::ROLES)['Company Admin'] && auth()->user()->companyUser->company_id == $this->company_user->company_id)) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getDocumentsAttribute()
    {
        return $this->getMedia('documents');
    }
}
