<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TAssistance extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'request_id',
        'request_by',
        'division_id',
        'request_type',
        'description',
        'request_date',
        'file_attachement',
        'status',
    ];

    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function request_by()
    {
        return $this->belongsTo('App\Models\User', 'request_by');
    }

    public function response()
    {
        return $this->hasOne(TAssistanceResponses::class, 'ta_request_id');
    }
}
