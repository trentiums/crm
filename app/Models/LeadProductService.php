<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadProductService extends Model
{
    use HasFactory;

    public $table = 'lead_product_service';

    protected $fillable = [
        'lead_id',
        'product_service_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function user()
    {
        return $this->belongsTo(ProductService::class, 'product_service_id');
    }
}
