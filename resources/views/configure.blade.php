@extends('layouts.app')

@section('content')
    <div class="container">
        {{--@if(Auth::user()->modules->isEmpty())--}}
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-success">

                    <div class="panel-heading">Device configuration missing</div>

                    <div class="panel-body">

                        <div class="stepwizard">
                            <div class="stepwizard-row setup-panel">
                                <div class="stepwizard-step">
                                    <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                    <p>Step 1</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-2" type="button" class="btn btn-default btn-circle"
                                       disabled="disabled">2</a>
                                    <p>Step 2</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-3" type="button" class="btn btn-default btn-circle"
                                       disabled="disabled">3</a>
                                    <p>Step 3</p>
                                </div>
                            </div>
                        </div>
                        <form role="form">
                            <div class="row setup-content" id="step-1">
                                <div class="col-xs-12">
                                    <div class="col-md-12">
                                        <h3> Step 1</h3>
                                        <div id="module" data-toggle="validator" role="form">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <label for="key">Module Key</label>
                                                <input type="text" name="module_key" class="form-control" id="key"
                                                       placeholder="Module ID" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="pin">Module Pin</label>
                                                <input type="text" name="module_pin" class="form-control" id="pin"
                                                       placeholder="Module Key" required>
                                            </div>
                                        </div>


                                        <button class="btn btn-primary nextBtn btn-lg pull-right" type="button">
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row setup-content" id="step-2">
                                <div class="col-xs-12">
                                    <div class="col-md-12">
                                        <h3> Step 2</h3>

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


                                        <button class="btn btn-primary nextBtn btn-lg pull-right" type="button">
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row setup-content" id="step-3">
                                <div class="col-xs-12">
                                    <div class="col-md-12">
                                        <h3> All done </h3>
                                        <button class="btn btn-success btn-lg pull-right finish-btn" type="submit">Finish!
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        {{--@endif--}}
    </div>
@endsection
