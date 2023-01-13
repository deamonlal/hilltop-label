<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLabelsRelation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'lable_id',
    ];
    
    public function label() {
        return $this->belongsTo(Label::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
