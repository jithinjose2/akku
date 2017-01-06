@extends('layouts.app')

@section('content')
    <div class="container">
        @if(Auth::user()->modules->isEmpty())
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
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
                </div>
            </div>

            
        @endif
    </div>
    <div class="SEID">
<!-- Rectangular switch -->
<label class="switch enabled">
  <input type="checkbox" data-id="1">
  <div class="slider round"></div>
</label>

<!-- Rounded switch -->
<label class="switch enabled">
  <input type="checkbox" data-id="2">
  <div class="slider round"></div>
</label>
            </div>
@endsection

