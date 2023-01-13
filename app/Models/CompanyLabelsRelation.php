<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLabelsRelation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'company_id',
        'lable_id',
    ];
    
    public function label() {
        return $this->belongsTo(Label::class);
    }
    
    public function company() {
        return $this->belongsTo(Company::class);
    }
}
