@extends('layouts.chatter_main')

@section('content')
    <script>window.course_id='{{$course_id}}';</script>

    <div class="d-none" id="toast-template-container-novariant">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">

            </div>
        </div>
    </div>
    <div class="d-none" id="toast-template-container-warning">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-warning">
                <strong class="me-auto"><i class="fas fa-exclamation"></i> Warning</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">

            </div>
        </div>
    </div>
    <div class="d-none" id="toast-template-container-success">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto"><i class="fas fa-check"></i> Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">

            </div>
        </div>
    </div>
    <div id="toast-container" class="toast-container position-fixed p-3 top-0 end-0">

    </div>

    <div id="app">
        <div id="loading-splash" style="height:100%;position: relative;">
            <div style="margin: 0;position: absolute;top: 50%;left: 50%;margin-right: -50%;transform: translate(-50%, -50%);">
                <h2>Loading...</h2>
            </div>
        </div>
        <app-framework></app-framework>
    </div>
@endsection
