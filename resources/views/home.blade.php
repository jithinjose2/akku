@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">

                    <div class="row">
                        @include('home-head')
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            @include('graph')
                        </div>
                        <div class="col-md-3">
                            @include('home-side')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('script')

@endsection
