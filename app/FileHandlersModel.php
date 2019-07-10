<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileHandlersModel extends Model
{
    protected $table = 'files_db';

    protected $fillable = [
        'original_file_name',
        'file_name',
        'file_storage',
        'file_url',
        'file_status',
        'file_error_info',
    ];
}
