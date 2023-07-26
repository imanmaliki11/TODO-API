<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDoModel extends Model
{
    use HasFactory;

    protected $table = "to_do";
    protected $fillable = [
        "parent",
        "title",
        "progress",
        "status",
        "due_date",
        "note"
    ];

    public function getParent() {
        return ParentToDoModel::find($this->parent);
    }
}
