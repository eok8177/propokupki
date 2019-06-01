@extends('backend.layouts.admin')

@section('content')
    <div class="container">
        <h1 class="title-page">Новая акция</h1>
    </div>
    <hr>

    <div class="actions">
        {!! Form::open(['route' => ['admin.discounts.store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            @include('backend.discounts.form')
        {{ Form::close() }}
    </div>


@endsection

@push('styles')
    <link href="{{ asset('vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/datepicker/locales/bootstrap-datepicker.ru.min.js') }}"></script>
    <script>
        $(function() {
            $('.date input').datepicker({
                language: "ru"
            });
            //calculate discount
            $('body').on('change', '.price, .discount', function(){
                var fields = $(this).parent();
                var price = fields.find('.price').val();
                var discount = fields.find('.discount').val();
                fields.find('.result').text(price - price*discount/100);
            });

            $('body').on('change', '.input-img', function(e) {
                handleImage(e);
            });

        });
    </script>
@endpush