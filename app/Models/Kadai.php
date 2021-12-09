<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Kadai extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'kadai';

    protected $fillable = [
        'name',
        'target'
    ];

    public $sortable = [
        'id',
        'name',
        'target'
    ];
}
