@extends('cutup.admin.layout')

@section('content')

    <div class="shops">
        <div class="container">
            {!! Form::open(['route' => ['admin.shops.update', $shop->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
            @include('backend.shops.form')
            {{ Form::close() }}
        </div>
    </div>

@endsection