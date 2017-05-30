<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class TodoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * getting all items in User model for user who logged in using "Auth::user()->todo()->get();"
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        $result = Auth::user()->todo()->get();
        if (!$result->isEmpty()) {
            return view('todo.dashboard', ['todos' => $result, 'image'=>Auth::user()->userimage]);
        } else {
            return view('todo.dashboard', ['todos' => false, 'image' => Auth::user()->userimage]);
        }
    }

    /**
     * Validate form fields to make sure the fields is not empty
     * @param array $request
     * @return mixed $request todo, desc, category
     */
    public function validator(array $request)
    {
        return Validator::make($request, [
           'todo' => 'required',
            'description' => 'required',
            'category' => 'required'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('todo.addtodo');
    }

    /**
     * Store a newly created resource in storage (new Todo items).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate the fields
        //save data to todo table
        $this->validator($request->all())->validate();
        if (Auth::user()->todo()->Create($request->all())) {
            return $this->index();
        }
    }

    /**
     * Display the specified resource, Single Todo Items.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
        return view ('todo.todo', ['todo' => $todo]);
    }

    /**
     * Show the form for editing the specified resource (single todo items).
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        //
        return view('todo.edittodo', ['todo' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        //validate fields and update todo
        $this->validator($request->all())->validate();
        if ($todo->fill($request->all())->save()) {
            return $this->show($todo);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Remove Todo Items
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        //
        if ($todo->delete()) {
            return back();
        }
    }
}
