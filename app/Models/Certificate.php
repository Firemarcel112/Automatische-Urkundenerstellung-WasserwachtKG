<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{

    protected $primaryKey = 'certificate_id';

    protected $guarded = ['certificate_id'];

    protected $casts = [
        'fields' => 'array',
    ];
}
