@extends('layouts.chatter_main')

@section('content')
    <div id="app">
        <div id="loading-splash" style="height:100%;position: relative;">
            <div style="margin: 0;position: absolute;top: 50%;left: 50%;margin-right: -50%;transform: translate(-50%, -50%);">
                <h2>Loading...</h2>
            </div>
        </div>
        <app-framework></app-framework>
    </div>
@endsection
