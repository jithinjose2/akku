@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Registred Modules</div>
                    <div class="panel-body">
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
                                    <span class="pull-right"><a class="btn btn-primary btn-xs"
                                                                href="/module/edit/{{$module->id}}">Configure</a></span>
                                </div>
                                @if(count($module->things) >0)
                                    <div class="panel-body">
                                        <ul class="list-group">
                                            @foreach($module->things as $thing)
                                                <li class="list-group-item">
                                                    <span class="badge"></span>
                                                    {{ $thing->name }}
                                                    <span class="bg-gray"> #{{ $thing->key }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
