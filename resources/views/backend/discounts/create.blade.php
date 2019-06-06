@extends('cutup.admin.layout')

@section('content')

    <div class="container">
        <h1 class="title-page">Новый магазин</h1>
    </div>
    <hr>

    <div class="shop-edit">
        {!! Form::open(['route' => ['admin.shops.store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="container">
            @include('backend.shops.form')
        </div>
        {{ Form::close() }}
    </div>


@endsection