@extends('cutup.admin.layout')

@section('content')

    <div class="shops">
        <div class="container">
            {!! Form::open(['route' => ['admin.cities.store'], 'method' => 'POST']) !!}
            @include('backend.cities.form')
            {{ Form::close() }}
        </div>
    </div>


@endsection