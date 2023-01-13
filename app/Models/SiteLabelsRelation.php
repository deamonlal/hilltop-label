<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteLabelsRelation extends Model {

    use HasFactory;

    protected $fillable = [
        'site_id',
        'lable_id',
    ];

    public function label() {
        return $this->belongsTo(Label::class);
    }

    public function site() {
        return $this->belongsTo(Site::class);
    }
}
