<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TAssistance extends Model
{
    //
    protected $fillable = [
        'request_id',
        'user_id',
        'division_id',
        'request_by',
        'request_type',
        'description',
        'request_date',
        'file_attachement',
        'status',
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }
}
