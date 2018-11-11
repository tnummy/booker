@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">Add New</div>

                    <div class="panel-body">
                        @if (isset($dependency))
                            <form class="form-horizontal" method="POST" action="{{ route('updateDependency', $dependency->id) }}">
                        @else
                            <form class="form-horizontal" method="POST" action="{{ route('storeDependency') }}">
                        @endif

                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('dependency_type') ? ' has-error' : '' }}">
                                <label for="dependency_type" class="col-md-4 control-label">Type</label>

                                <div class="col-md-6">
                                    <select id="dependency_type" class="form-control" name="dependency_type" required autofocus>
                                        <option value="" disabled selected>Choose a Type</option>
                                        @if (isset($dependency))
                                            @foreach ($dependency_types as $dependency_type)
                                                <option value="{{$dependency_type->id}}" @if($dependency_type->id == $dependency->user_dependency_type_id) selected @endif>{{$dependency_type->description}}</option>
                                            @endforeach
                                        @else
                                            @foreach ($dependency_types as $dependency_type)
                                                <option value="{{$dependency_type->id}}" >{{$dependency_type->description}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('dependency_type'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('dependency_type') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="test" class="form-control" name="name" placeholder="Name" value="{{ $dependency->name or ''}}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        @if (isset($dependency))
                                            Update
                                        @else
                                            Add New
                                        @endif
                                    </button>
                                </div>
                            </div>

                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
