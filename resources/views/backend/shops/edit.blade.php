@extends('backend.layouts.admin')

@section('content')

    <div class="shop-edit">
        <div class="container">
            {!! Form::open(['route' => ['admin.shops.update', $shop->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
            @include('backend.shops.form')
            {{ Form::close() }}
        </div>
    </div>

@endsection