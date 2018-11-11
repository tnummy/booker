<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dependency;
use App\Models\DependencyType;
use App\Models\UserDependencyPermission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DependencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId  = Auth::id();
        $dependency_types = DependencyType::
            whereHas('user_permissions', function($q) use ($userId){
                $q->where('user_id', $userId);
            })
            ->get();

        return view('dependencies/create')->with(['dependency_types' => $dependency_types]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId  = Auth::id();
        $name = $request->input('name');
        $type   = $request->input('dependency_type');

        $data = [
            'name'                    => $name,
            'user_id'                 => $userId,
            'user_dependency_type_id' => $type,
            ];

        Dependency::create($data);

        $message = sprintf('%s was saved!', $name);

        $request->session()->flash('status', $message);

        return redirect('/dependency/all');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userId  = Auth::id();
        $dependency = Dependency::where('id', $id)->firstorfail();

        $dependency_types = DependencyType::with('user_permissions')
            ->whereHas('user_permissions', function($q) use ($userId){
                $q->where('user_id', $userId);
            })
            ->get();;
        return view('dependencies/create')->with(['dependency' => $dependency, 'dependency_types' => $dependency_types]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dependency = Dependency::where('id', $id)->firstorfail();

        $dependency->user_id                 = Auth::id();
        $dependency->name                    = $request->input('name');
        $dependency->user_dependency_type_id = $request->input('dependency_type');

        $dependency->save();

        $message = sprintf('%s was updated!', $dependency->name);

        $request->session()->flash('status', $message);

        return redirect('/dependency/all');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $dependency = Dependency::find($request->id);

        $dependency->delete();

        $message = sprintf('%s was deleted!', $dependency->name);

        $request->session()->flash('status', $message);

        return redirect('/dependency/all');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $dependency = Dependency::
            withTrashed()->find($request->id);

        $dependency->restore();

        $message = sprintf('%s was restored!', $dependency->name);

        $request->session()->flash('status', $message);

        return redirect('/dependency/all');
    }
}
