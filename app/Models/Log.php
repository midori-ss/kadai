<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Log extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'log';

    protected $fillable = [
        'user_code',
        'user_name',
        'type'
    ];
}
