@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Create New Rule</h3>
            </div>
            <form method="post" id="addRule" class="form-horizontal" action="<?php echo url("rule-creation"); ?>">

                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-lg-2">
                            <strong>Rule Name</strong>
                        </div>
                        <label class="col-lg-1 control-label"></label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="rule_name" placeholder="Rule Name" required/>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-lg-2">
                            <strong>Set Trigger</strong>
                        </div>
                        <label class="col-lg-1 control-label">When</label>
                        <div class="col-lg-3">
                            <select class="form-control" name="action_thing" required>
                                <option value="">-- Select --</option>
                                @foreach($modules as $module)
                                    @foreach($module->things as $thing)
                                        @if($thing->type==2)
                                            <option value="{{ $thing->id }}"
                                            >{{ $thing->name }}</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <label class="col-lg-1 control-label">Value</label>
                        <div class="col-lg-2">
                            <select class="form-control" name="action_match" required>
                                <option value="=">Is equals to (=)</option>
                                <option value="<">Is less than (<)</option>
                                <option value=">">Is greater than (>)</option>
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <input type="text" class="form-control" name="trigger_value" placeholder="value" required/>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-lg-2">
                            <strong>Associate Action</strong>
                        </div>
                        <label class="col-lg-1 control-label">Act</label>
                        <div class="col-lg-3">
                            <select class="form-control" name="turn_action" required>
                                <option value="1">turn on</option>
                                <option value="0">turn off</option>
                            </select>
                        </div>
                        <label class="col-lg-1 control-label">Switch</label>
                        <div class="col-lg-4">
                            <select class="form-control" name="switch_selection" required>
                                <option value="">-- Select --</option>
                                @foreach($modules as $module)
                                    @foreach($module->things as $thing)
                                        @if($thing->type==1)
                                            <option value="{{ $thing->id }}">{{ $thing->name }}</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary pull-right" name="rule" value="add-rule">Add New Rule
                    </button>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>

    @if(count($rules))
        <div class="container">
            <div class="row">
                <div class="alert alert-warning">
                    <strong>Rules List</strong>
                </div>
                <table id="status-table" class="table table-striped table-inverse table-bordered"
                       style="font-size: 1.4rem;">
                    <thead class="thead-inverse">
                    <tr>
                        <th width="100px">Rule ID</th>
                        <th width="300px">Rule Name</th>
                        <th width="100px">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rules as $rule)
                        <?php
                        $counter = 1;
                        ?>
                        <tr>
                            <td scope="row">{{ $counter++ }}</td>
                            <td>{{ $rule->name }}</td>
                            <td>
                                <form method="post" action="<?php echo url("rule-deletion"); ?>">
                                    <input type="hidden" name="rule_id" value="{{ $rule->id }}"/>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                    <button type="submit" class="btn btn-primary" name="delete_rule"
                                            value="delete-rule">Delete
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
