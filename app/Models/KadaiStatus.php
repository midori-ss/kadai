<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KadaiStatus extends Model
{
    use HasFactory;
    protected $table = 'kadai_status';

    protected $fillable = [
        'kadai_id',
        'user_code',
        'status'
    ];
}
