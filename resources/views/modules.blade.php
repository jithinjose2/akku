@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h3>Registered Modules</h3>
                <hr>
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
            </div>
        </div>
    </div>
@endsection
