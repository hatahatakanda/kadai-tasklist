<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        if (\Auth::check() and \Auth::id()==$user_id) {
            $user=\Auth::user();
            $tasks=$user->tasks;
            return view('tasks.index',['tasks'=>$tasks,]);
        }
        else {
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id)
    {
        if (\Auth::check() and \Auth::id()==$user_id) {
            $task=new Task;
            return view('tasks.create',['task'=>$task,]);
        }
        else {
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::check() and \Auth::id()==$user_id) {
            $this->validate($request, ['status' => 'required|max:10','content' => 'required|max:191',]);
            $task = new Task;
            $task->status =$request->status;
            $task->content = $request->content;
            $task->user_id= \Auth::id();
            $task->save();
            return redirect('/');
        }
        else {
            return redirect('/');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id,$id)
    {
        if (\Auth::check() and \Auth::id()==$user_id and null !==\Auth::user()->tasks()->find($id)) {
            $task = Task::find($id);
            return view('tasks.show', ['task' => $task,]);
            
        }
        else {
            return view('welcome');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check() and \Auth::id()==$user_id) {
            $task = Task::find($id);
            return view('tasks.edit', ['task' => $task,]);
        }
        else {
            return view('welcome');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['status' => 'required|max:10','content' => 'required|max:191',]);
        $task = Task::find($id);
        if ((\Auth::id() === $task->user_id)){
            $task->status =$request->status;
            $task->content = $request->content;
            $task->user_id= \Auth::id();
            $task->save();
        }
        

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if ((\Auth::id() === $task->user_id)){
            $task->delete();
        }
        return redirect('/');
    }
}
