<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TAssistanceResponses extends Model
{
    //
    protected $table = 'ta_assistance_responses';

    protected $fillable = [
        'ta_request_id',
        'findings',
        'recommendations',
        'remarks',
        'performed_by',
    ];

    public function t_assistance()
    {
        return $this->belongsTo(TAssistance::class, 'ta_request_id');
    }
}
