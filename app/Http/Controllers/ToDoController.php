<?php

namespace App\Http\Controllers;

use App\Models\ParentToDoModel;
use App\Models\ToDoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToDoController extends Controller
{
    protected function getUser() {
        return auth('sanctum')->user();
    }

    public function getAllParentToDo() {
        $parent = ParentToDoModel::all();
        return response([
            "message" => "Success get All Parent to do.",
            "status" => true,
            "code" => 200,
            "data" => $parent
        ]);
    }

    public function getAllParentToDoByToken() {
        $user = auth('sanctum')->user();
        $parent = ParentToDoModel::where("user_id", $user->id)->get();
        return response([
            "message" => "Success get All Parent to do By Token",
            "status" => true,
            "code" => 200,
            "data" => $parent
        ]);
    }

    public function getParentToDoById($id) {
        $parent = ParentToDoModel::find($id);
        
        if($parent && $parent->is_public) {
            return response([
                "message" => "Success get Parent to do By Id",
                "status" => true,
                "code" => 200,
                "data" => $parent
            ]);
        } else if($parent) {
            $user = auth('sanctum')->user();
            if($parent->user_id == $user->id) {
                return response([
                    "message" => "Success get Parent to do By Id",
                    "status" => true,
                    "code" => 200,
                    "data" => $parent
                ]);
            }
            return response([
                "status" => false,
                "code" => 400,
                "message" => "This parent to do is private"
            ], 400);
        }

        return response([
            "status" => false,
            "code" => 400,
            "message" => "Invalid To Do ID"
        ], 400);
    }

    public function createParentToDo(Request $req) {
        $rules = [
            "title" => "required",
            "is_public" => "boolean"
        ];

        $valid = Validator::make($req->all(), $rules);
        if($valid->fails()) {
            return response([
                "status" => false,
                "code" => 400,
                "message" => $valid->errors()->first()
            ], 400);
        }
        
        $parent = new ParentToDoModel($req->all());
        $parent->user_id = auth('sanctum')->user()->id;
        $parent->save();
        return response([
            "status" => true,
            "code" => 201,
            "message" => "Success add parent to do",
            "data" => $parent
        ], 201);
    }

    public function updateParentToDo(Request $req, $id) {
        $rules = [
            "title" => "min:3",
            "is_public" => "boolean"
        ];

        $valid = Validator::make($req->all(), $rules);

        if($valid->fails()) {
            return response([
                "code" => 400,
                "status" => false,
                "message" => $valid->errors()->first()
            ], 400);
        }

        $validated = $valid->safe()->only(["title", "is_public"]);

        $parentToDo = ParentToDoModel::find($id);

        if(!$parentToDo) {
            return response([
                "code" => 400,
                "status" => false,
                "message" => "Invalid Parent To Do ID"
            ], 400);
        }

        $user = $this->getUser();
        if($parentToDo->user_id != $user->id) {
            return response([
                "code" => 403,
                "status" => false,
                "message" => "You not have an access to update this Parent To Do"
            ], 403);
        }

        $parentToDo->fill($validated);
        $parentToDo->save();

        return response([
            "code" => 200,
            "status" => true,
            "message" => "Success update Parent To Do",
            "data" => $parentToDo
        ], 200);
    }

    public function deleteParentToDo($id) {
        $user = $this->getUser();
        $parent = ParentToDoModel::find($id);

        if(!$parent) {
            return response([
                "code" => 400,
                "status" => false,
                "message" => "Invalid Parent To Do ID"
            ], 400);
        }

        if($user->id != $parent->user_id){
            return response([
                "code" => 403,
                "status" => false,
                "message" => "You not have an access to this Parent To Do"
            ], 403);
        }

        ToDoModel::where("parent", $id)->delete();
        $parent->delete();
        
        return response([
            "code" => 200,
            "status" => true,
            "message" => "Success delete Parent To Do",
            "data" => $parent
        ], 200);
    }


    //Section for TO DO
    public function createToDo(Request $req) {
        $rules = [
            "title" => "required",
            "due_date" => "date_format:Y-m-d H:i:s",
            "parent" => "required|exists:parent_todo,id",
            "status" => "in:done,created,progress",
            "progress" => "numeric|max:100|min:0",
            "note" => "min:1"
        ];
        
        $valid = Validator::make($req->all(), $rules);
        if($valid->fails()) {
            return response([
                "message" => $valid->errors()->first(),
                "status" => false,
                "code" => 400,
            ], 400);
        }

        $data_valid = $valid->safe()->only(['title','due_date','note','parent','status','progress']);
        
        $user = $this->getUser();
        $parent = ParentToDoModel::find($req->json("parent"));
        if($user->id != $parent->user_id) {
            return response([
                "message" => "You not have an access to add TODO in this parent",
                "status" => false,
                "code" => 403,
            ], 403);
        }

        $todo = new ToDoModel($data_valid);
        $todo->save();

        return response([
            "message" => "Success add To Do",
            "status" => true,
            "code" => 201,
            "data" => $todo
        ], 201);
    }

    public function getAllToDo() {
        $todo = ToDoModel::all();
        return response([
            "status" => true,
            "code" => 200,
            "message" => "Success get all to do",
            "data" => $todo
        ], 200);
    }

    public function getAllToDoByToken() {
        $user = auth('sanctum')->user();
        $parentToDo = ParentToDoModel::where("user_id", $user->id)->get();
        $data = [];
        foreach($parentToDo as $p) {
            $todo = ToDoModel::where("parent", $p->id)->get();
            $list_todo = [];
            foreach($todo as $t) {
                $list_todo[] = $t;
            }
            $data[] = [
                "todo" => $list_todo,
                "parent" => $p,
                "is_mytodo" => true
            ];
        }
        return response([
            "status" => true,
            "code" => 200,
            "message" => "Success get all to do By Token",
            "data" => $data
        ], 200);
    }

    public function getAllToDoByParentId($parentId) {
        $user = $this->getUser();
        $parent = ParentToDoModel::find($parentId);
        if($parent && ($parent->is_public || $parent->user_id == $user->id)) {
            $todo = ToDoModel::where('parent', $parentId)->get();
            $data = [
                "todo" => $todo,
                "parent" => $parent,
                "is_mytodo" => $parent->user_id == $user->id
            ];
            return response([
                "status" => true,
                "code" => 200,
                "message" => "Success get all to do",
                "data" => $data
            ], 200);
        }
        if($parent) {
            return response([
                "status" => false,
                "code" => 403,
                "message" => "Parent To Do is private"
            ], 403);
        }
        return response([
            "status" => false,
            "code" => 400,
            "message" => "ParentId Invalid"
        ], 400);
    }

    public function getToDoById($id) {
        $todo = ToDoModel::find($id);
        if($todo) {
            $parent = ParentToDoModel::find($todo->parent);
            $user = $this->getUser();
            if(($parent && $parent->is_public) || ($parent && $parent->user_id == $user->id)) {
                $todo->data_parent = $parent;
                $todo->is_mytodo = $parent->user_id == $user->id;
                return response([
                    "status" => true,
                    "code" => 200,
                    "message" => "Success get all to do",
                    "data" => $todo
                ], 200);
            } else {
                return response([
                    "status" => false,
                    "code" => 403,
                    "message" => "To Do is private"
                ], 403);
            }
        }
        return response([
            "status" => false,
            "code" => 400,
            "message" => "Invalid To Do ID"
        ], 400);
    }

    public function updateToDo(Request $req, $id) {
        $rules = [
            "title" => "min:3",
            "due_date" => "date_format:Y-m-d H:i:s",
            "status" => "in:done,created,progress",
            "progress" => "numeric|max:100|min:0",
            "note" => "min:3"
        ];

        $valid = Validator::make($req->all(), $rules, []);
        if($valid->fails()) {
            return response([
                "code" => 400,
                "status" => false,
                "message" => $valid->errors()->first()
            ], 400);
        }

        $validated = $valid->safe()->only(["title","due_date","status","progress","note"]);

        $todo = ToDoModel::find($id);

        if(!$todo) {
            return response([
                "code" => 400,
                "status" => false,
                "message" => "Invalid To Do ID"
            ], 400);
        }

        $user = $this->getUser();
        if($user->id != $todo->getParent()->user_id) {
            return response([
                "code" => 403,
                "status" => false,
                "message" => "You not have an access to this To Do"
            ], 403);
        }

        $todo->fill($validated);
        $todo->save();

        return response([
            "code" => 200,
            "status" => true,
            "message" => "Success Update To Do",
            "data" => $todo
        ], 200);
        
    }

    public function deleteToDo($id) {
        $user = $this->getUser();
        $todo = ToDoModel::find($id);
        if(!$todo) {
            return response([
                "code" => 400,
                "status" => false,
                "message" => "Invalid To Do ID"
            ], 400);
        }

        if($todo->getParent()->user_id != $user->id){
            return response([
                "code" => 403,
                "status" => false,
                "message" => "You not have an access to this To Do"
            ], 403);
        }

        $todo->delete();

        return response([
            "code" => 200,
            "status" => true,
            "message" => "Success delete To Do",
            "data" => $todo
        ], 200);
    }
}
