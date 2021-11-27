<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kadai extends Model
{
    use HasFactory;

    protected $table = 'kadai';

    protected $fillable = [
        'name',
        'target'
    ];
}
