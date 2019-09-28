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
        if (\Auth::check()  and \Auth::id()==$user_id) {
            $user=\Auth::user();
            $tasks=$user->tasks;
            $user_id=\Auth::id();
            return view('tasks.index',['user_id'=>$user_id,'tasks'=>$tasks,]);
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
            return view('tasks.create',['user_id'=>$user_id,'task'=>$task,]);
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
    public function store(Request $request,$user_id)
    {
        if (\Auth::check() and \Auth::id()==$user_id) {
            $this->validate($request, ['status' => 'required|max:10','content' => 'required|max:191',]);
            $task = new Task;
            $task->status =$request->status;
            $task->content = $request->content;
            $task->user_id= \Auth::id();
            $task->save();
            return redirect()->action('TasksController@index', ['user_id' => \Auth::id()]);
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
            return view('tasks.show', ['user_id'=>$user_id,'task' => $task,]);
            
        }
        else {
            return redirect('/');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id,$id)
    {
        if (\Auth::check() and \Auth::id()==$user_id) {
            $task = Task::find($id);
            return view('tasks.edit', ['user_id'=>$user_id,'task' => $task,]);
        }
        else {
            return redirect('/');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id,$id)
    {
        $this->validate($request, ['status' => 'required|max:10','content' => 'required|max:191',]);
        $task = Task::find($id);
        if ((\Auth::check() and \Auth::id() == $task->user_id)){
            $task->status =$request->status;
            $task->content = $request->content;
            $task->user_id= \Auth::id();
            $task->save();
            return redirect()->action('TasksController@index', ['id' => $user_id]);
        }
        else {
            return redirect('/');
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id,$id)
    {
        $task = Task::find($id);
        if ((\Auth::check() and \Auth::id() == $task->user_id)){
            $task->delete();
            return redirect()->action('TasksController@index', ['id' => $user_id]);
        }
        return redirect('/');
    }
}
