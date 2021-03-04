<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
class TodoController extends Controller
{
    //create
	public function create(Request $request)
	{
		$item= new Todo;
		$item->done = $request->done;
		$item->text = $request->text;
		$item->users = $request->user()->id;
		$item->save();

        return response() ->json([
            "message" => "Todo Item has been created",
            "id" =>$item->id
        ],201);        
	}
	//read
	public function read(Request $request)
	{
		$item= Todo::where('users',$request->user()->id)->get();
		return response($item, 200); 
	}
	//update
	public function edit(Request $request,$id)
	{
		$item=Todo::where('users',$request->user()->id)->where('id',$id)->first();
		$item->done = $request->done;
		$item->text = $request->text;
		$item->save();

        return response() ->json([
            "message" => "Todo Item has been deleted",
        ],201); 
	}
	//delete
	public function remove(Request $request,$id)
	{
		Todo::where('users',$request->user()->id)->where('id',$id)->delete();
        return response() ->json([
            "message" => "Todo Item has been deleted",
        ],201); 
	}
}
