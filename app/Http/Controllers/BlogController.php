<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Validator;
use App\Http\Resources\Blog as BlogResource;
use App\Http\Controllers\BaseController;

class BlogController extends BaseController
{
    public function index(){
        $blog = Blog::all();
        return $this->sendResponse(new BlogResource($blog),"Posztok betöltve");
    }
    public function show($id){
        $blog = Blog::find($id);
        if(is_null($blog)){
            return $this->sendError("", "Nincs adat!");
        }
        return $this->sendResponse(new BlogResource($blog),"Poszt betöltve");
    }
    public function destroy(Blog $blog){
        $blog->delete();
        return $this->sendResponse("","Törlés sikeres!");
    }
    public function update(Request $request, Blog $blog){
        $input = $request->all();
        $validator = Validator::make($input,[
            "title" => "required",
            "description" => "required"
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors("Validálási hiba", $validator->errors()));
        }
        $blog->title = $input["title"];
        $blog->description = $input["description"];
        $blog->save();
        return $this->sendResponse(new BlogResource($blog),"Poszt módosítva");
    }
    public function store(Request $request){
        // dd($request);
        $input = $request->all();
        $validator = Validator::make($input,[
            "title" => "required",
            "description" => "required"
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors("Validálási hiba", $validator->errors()));
        }
        $blog = Blog::create($input);
        return $this->sendResponse($blog, "Sikeres hozzáadás");
    }
}
