@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <form method="post" action="/module/{{$module->id}}">
                <div class="col-md-8">
                    {{csrf_field()}}
                    {{ method_field('PUT') }}
                    <div class="panel panel-default">
                        <div class="panel-heading">Modules info</div>
                        <div class="panel-body">
                            <fieldset>
                                <legend>Module Name</legend>
                                <div class="form-group">
                                    <label for="module_name">Module Name</label>
                                    <input type="text" name="module_name" class="form-control"
                                           id="module_name"
                                           value="{{$module->name}}"
                                           placeholder="module name">
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Switches info</div>
                        <div class="panel-body">
                            <fieldset>
                                @foreach($things as $thing)
                                    <div class="col-md-12">
                                        <div class="form-group col-md-6">
                                            <label for="{{$thing->key}}">{{$thing->name}}</label>
                                            <input type="text" name="{{$thing->key}}" class="form-control"
                                                   id="{{$thing->key}}"
                                                   value="{{$thing->name}}"
                                                   placeholder="module name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="{{$thing->key}}">{{$thing->name}} status</label>
                                            <input type="checkbox" name="{{$thing->key}}" class="form-control"
                                                   id="{{$thing->key}}"
                                                   value="{{$thing->name}}"
                                                   placeholder="module name">
                                        </div>
                                    </div>

                                @endforeach

                            </fieldset>
                        </div>
                    </div>
                    <p class="text-center">
                        <button class="btn btn-primary">
                            Submit
                        </button>
                    </p>
                </div>
                <div class="col-md-4">


                    {{csrf_field()}}
                    {{ method_field('PUT') }}
                    <div class="panel panel-default">
                        <div class="panel-heading">Users</div>
                        <div class="panel-body">
                            @foreach($users as $user)
                                <div class="form-group">
                                    <label class="col-md-6" for="users">{{$user->name}}</label>
                                    <div class="col-md-6">
                                        <input type="checkbox"
                                               @if(in_array($user->id,$moduleusers))
                                               checked
                                               @endif
                                               name="users[]"
                                               id="users"
                                               value="{{$user->id}}">
                                    </div>
                                    <div class="clearfix"></div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
