@extends('cutup.admin.layout')

@section('content')

    <div class="discounts">
        <div class="container">
            {!! Form::open(['route' => ['admin.discounts.update', $discount->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
            @include('backend.discounts.form')
            {{ Form::close() }}
        </div>
    </div>

@endsection