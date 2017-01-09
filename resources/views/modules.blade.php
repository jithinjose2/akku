@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h3>Registered Modules</h3>
                <hr>
                @if(Auth::user()->modules->isEmpty())
                    <div class="panel panel-warning">
                        <div class="panel-heading">Device configuration missing</div>
                        <div class="panel-body">
                            <p>Please configure your device</p>
                            <p class="text-center">
                                <a class="btn btn-success" href="/configure"> setup your first
                                    device</a>
                            </p>
                        </div>
                    </div>
                @else
                    @foreach($modules as $module)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                @if($module->status)
                                    <span class="badge bg-green">Online</span>
                                @else
                                    <span class="badge bg-red">Offline</span>
                                @endif
                                {{ $module->name }}
                                <span class="bg-gray"> #{{ $module->key }}</span>
                                <span class="pull-right">
                                        <a class="btn btn-primary btn-xs"
                                           href="/module/{{$module->id}}/edit">Configure</a>
                                    </span>

                            </div>
                            @if(count($module->things) >0)
                                <div class="panel-body">
                                    @foreach($module->things as $thing)
                                        <div class="col-md-4">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <p>Name : {{ $thing->name }}</p>
                                                    <p>Key : {{ $thing->key }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="panel-footer">
                              <span class="pull-right">
                                        <a class="btn btn-warning btn-xs"
                                           href="/module/{{$module->id}}/edit">Deactive</a>
                                    </span>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                    @endforeach
                @endif
                <h3>Accessible Modules</h3>
                <hr>

                @if(Auth::user()->assignedModules->isEmpty())
                    <p>You don't have access to other modules</p>
                    <br>
                    <br>
                    <br>
                @else
                    @foreach(Auth::user()->assignedModules as $module)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                @if($module->status)
                                    <span class="badge bg-green">Online</span>
                                @else
                                    <span class="badge bg-red">Offline</span>
                                @endif
                                {{ $module->name }}
                                <span class="bg-gray"> #{{ $module->key }}</span>
                            </div>
                            @if(count($module->things) >0)
                                <div class="panel-body">
                                    @foreach($module->things as $thing)
                                        <div class="col-md-4">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    @if($thing->type===1)
                                                        <div class="rmmargin col-md-8 ">
                                                            <p>Name : {{ $thing->name }} </p>
                                                            <p>Key : {{ $thing->key }} </p>
                                                        </div>
                                                        <div class="rmmargin col-md-4">
                                                            <label class="switch enabled" style="margin-top: 24px">
                                                                <input class="switchChange" name="{{ $thing->id }}"
                                                                       id="{{ $thing->id }}"
                                                                       type="checkbox">
                                                                <div class="slider round"></div>
                                                            </label>
                                                        </div>
                                                    @else
                                                        <p>Name : {{ $thing->name }} </p>
                                                        <p>Key : {{ $thing->key }} </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
