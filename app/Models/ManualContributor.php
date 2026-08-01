<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualContributor extends Model
{
    protected $fillable = ['github_username', 'display_name', 'section', 'note'];
}
