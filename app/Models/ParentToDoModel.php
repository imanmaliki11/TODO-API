<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentToDoModel extends Model
{
    use HasFactory;
    protected $table = "parent_todo";
    protected $fillable = [
        "title",
        "user_id",
        "is_public"
    ];
}
