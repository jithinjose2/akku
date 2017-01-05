@extends('layouts.app')

@section('content')
    <div class="container">
        {{--@if(Auth::user()->modules->isEmpty())--}}
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-success">

                        <div class="panel-heading">Device configuration missing</div>

                        <div class="panel-body">
                            <div id="rootwizard">
                                <div class="navbar">
                                    <div class="navbar-inner">
                                        <ul>
                                            <li><a href="#tab1" data-toggle="tab">Module Identification</a></li>
                                            <li><a href="#tab2" data-toggle="tab">Module Properties</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div id="bar" class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                         aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane" id="tab1">
                                        <form id="module">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <label for="key">Module Key</label>
                                                <input type="text" name="module_key" class="form-control" id="key"
                                                       placeholder="Module ID">
                                            </div>
                                            <div class="form-group">
                                                <label for="pin">Module Pin</label>
                                                <input type="text" name="module_pin" class="form-control" id="pin"
                                                       placeholder="Module Key">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="tab2">
                                        <div class="tab-pane" id="tab1">
                                            <form id="switches">

                                                <fieldset>
                                                    <legend>Module Name</legend>
                                                    <div class="form-group">
                                                        <label for="module_name">Module Name</label>
                                                        <input type="text" name="module_name" class="form-control"
                                                               id="module_name"
                                                               placeholder="module name">
                                                    </div>
                                                </fieldset>

                                                <fieldset>
                                                    <legend>Switches Names</legend>
                                                    <div id="sw">
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>
                                    </div>

                                    <ul class="pager wizard">
                                        <li class="previous"><a href="#">Previous</a></li>
                                        <li class="next"><a href="#">Next</a></li>
                                        <li class="finish"><a href="javascript:;">Finish</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {{--@endif--}}
    </div>
@endsection
