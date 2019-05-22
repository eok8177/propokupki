@extends('cutup.admin.layout')

@section('content')

    <div class="shops">
        <div class="container">
            {!! Form::open(['route' => ['admin.shops.store'], 'method' => 'POST']) !!}
            @include('backend.shops.form')
            {{ Form::close() }}
        </div>
    </div>


@endsection