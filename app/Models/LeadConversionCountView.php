<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadConversionCountView extends Model
{
    use HasFactory;

    public $table = "lead_conversion_count_view";

    public function lead_conversion()
    {
        return $this->belongsTo(LeadConversion::class);
    }

    public function company_user()
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
}
