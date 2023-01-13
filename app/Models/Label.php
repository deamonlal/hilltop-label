<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model {

    use HasFactory;
    
    protected $fillable = [
        'name',
    ];

    public function userLabelsRelation() {
        return $this->hasMany(UserLabelsRelation::class);
    }
    
    public function companyLabelsRelation() {
        return $this->hasMany(CompanyLabelsRelation::class);
    }
    
    public function siteLabelsRelation() {
        return $this->hasMany(SiteLabelsRelation::class);
    }

    
}
