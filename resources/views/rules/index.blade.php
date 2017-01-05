@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
       <div class="alert alert-warning">
            <strong>Create New Rule</strong>
        </div>
    </div>
    
<form id="defaultForm" method="post" class="form-horizontal" action="<?php echo url("rule-creation"); ?>">
    <div class="form-group">
         <div class="col-lg-2">
            <div class="alert alert-info fade in">
                <strong>Rule Name</strong>
            </div>
        </div>  
        <label class="col-lg-1 control-label"></label>
        <div class="col-lg-2">
             <input type="text" class="form-control" name="rule_name" placeholder="Rule Name" />
        </div>
    </div>
    <div class="form-group"> 
        <div class="col-lg-2">
            <div class="alert alert-info fade in">
                <strong>Set Trigger</strong>
            </div>
        </div>                           
        <label class="col-lg-1 control-label">When</label>
        <div class="col-lg-2">
            <select class="form-control" name="action_thing">
                <option value="">-- Select --</option>
                @foreach($modules as $module)
                    @foreach($module->things as $thing)
                        @if($thing->type==2)
                            <option
                            value="{{ $thing->id }}"
                            >{{ $thing->name }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>
        <label class="col-lg-1 control-label">value</label>
        <div class="col-lg-2">
            <select class="form-control" name="action_match">
                <option value="=">Is equals to</option>
                <option value="<">Is less than</option>
                <option value=">">Is greater than</option>
            </select>
        </div>

        <div class="col-lg-1">
            <input type="text" class="form-control" name="trigger_value" placeholder="value" />
        </div>
    </div>

    <div class="form-group"> 
        <div class="col-lg-2">
            <div class="alert alert-info fade in">
                <strong>Associate Action</strong>
            </div>
        </div>                           
        <label class="col-lg-1 control-label">Act</label>
        <div class="col-lg-2">
            <select class="form-control" name="turn_action">
                <option value="1">turn on</option>
                <option value="0">turn off</option>
            </select>
        </div>
        <label class="col-lg-1 control-label">switch</label>
        <div class="col-lg-2">
            <select class="form-control" name="switch_selection">
                <option value="">-- Select --</option>
                @foreach($modules as $module)
                    @foreach($module->things as $thing)
                        @if($thing->type==1)
                            <option
                                    value="{{ $thing->id }}"
                            >{{ $thing->name }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>
    </div>
      <input type="hidden" name="_token" value="{{ csrf_token() }}" />
   
    <div class="form-group col-lg-offset-1">
        <div class="col-lg-9">
            <button type="submit" class="btn btn-primary" name="rule" value="add-rule">Add New Rule</button>
           
        </div>
    </div>
</form>
</div>

@if(count($rules))
<div class="container">
<div class="row">
       <div class="alert alert-warning">
            <strong>Rules List</strong>
        </div>
<table id="status-table" class="table table-striped table-inverse table-bordered" style="font-size: 1.4rem;">
                    <thead class="thead-inverse">
                        <tr>
                            <th width="100px">Rule ID</th>
                            <th width="300px">Rule Name</th>
                            <th width="100px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rules as $rule)
                        <tr>
                            <td scope="row">{{ $rule->id }}</td>
                            <td>{{ $rule->name }}</td>
                            <td>Active</td>

                        </tr>               
                    @endforeach                                                       
                    </tbody>
                </table> 
    </div>
</div>
@endif
@endsection
