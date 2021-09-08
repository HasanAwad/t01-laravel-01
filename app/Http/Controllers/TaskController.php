<?php

namespace App\Http\Controllers;

use App\Models\Task;
use File;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();

        return response()->json([
            "success"=>true,
            "tasks"=>$tasks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $task = new Task();

        $task->fill($request->all());
        
        if($image=$request->file('image'))
        {
            $image=$request->image;
            $image->store('public/images/');
            $task->image = $image->hashName();
        }

        if($task->save()){

            return response()->json([
                "success"=>true,
                "tasks"=>$task
            ]);
        }else{
            return response()->json([
                "success"=>false,
                "tasks"=>"task could not be added"
            ],500);
        }

        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        if($task){
            return response()->json([
                "success"=>true,
                "tasks"=>$task
            ],200);
        }
          return response()->json([
            "success"=>false,
            "message"=>"tasks could not be found"
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if($task){

            $task->update($request->all());
            if($image=$request->file('image')){


                
                if($this->imageDelete($task->image)){

                    $image=$request->image;
                    $image->store('public/storage/images/');
                    $task->image = $image->hashName();
                }else{
                    $image=$request->image;
                    //dd($image->store('public/storage/images'));
                    $image->store('public/storage/images/');
                    $task->image = $image->hashName();
                }
            }
            if($task->save()){
                return response()->json([
                    "success"=>true,
                    "tasks"=>$task
                ],200);
            }else{
    
                return response()->json([
                    "success"=>false,
                    "message"=>"could not be saved"
                ],500);
            }
        }


            return response()->json([
                "success"=>false,
                "message"=>"could not be found"
            ],500);
        
       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        //dd($task);

        if($task->delete()){

            if($this->imageDelete($task->image)){

                return response()->json([
                    "success"=>false,
                    "message"=>"didn't delete"
                ],401);
            }else{
              

                return response()->json([
                    "success"=>true,
                    "message"=>"deleted"
                ],200);

            }



        }
        else{
            return response()->json([
                "success"=>false,
                "message"=>"task could not be deleted"
            ],500);
        }
    }


    public function imageDelete($oldImage){

        if(File::exists(public_path('storage/images'.$oldImage)))
        {
            File::delete(public_path('storage/images'.$oldImage));

            return true;
        }
        else{
            return false;
        }
    }
}