@extends('backend.layouts.admin')

@section('content')

    <div class="container">
        <h1 class="title-page">Акции <span class="badge badge-pill badge-secondary">{{ count($discounts) }}</span></h1>
        <a href="{{ route('admin.discounts.create') }}" class="btn-noborder"><span class="ico ico-big ico-plus"></span> Новая акция</a>
    </div>
    <hr>

    <div class="actions">
        <div class="container">
            <div class="select">
                <select name="" class="custom-select" name="" id="status">
                    <option value="all" {{$status == 'all' ? 'selected="selected"' : ''}}>Все <span class="badge">{{ $header_discounts }}</span></option>
                    <option value="on" {{$status == 'on' ? 'selected="selected"' : ''}}>Активные <span class="badge">{{ $count_on }}</span></option>
                    <option value="off" {{$status == 'off' ? 'selected="selected"' : ''}}>не активные <span class="badge">{{ $count_off }}</span></option>
                </select>
            </div>

            {!! Form::open(['route' => ['admin.discounts.index'], 'method' => 'GET']) !!}
            <div class="search">
                <input name="search" id="shop_search" type="text" data-href="{{route('admin.shops.ajaxShops')}}" placeholder="Введите название" value="{{app('request')->input('search')}}">
                <button type="submit" class="btn-search"></button>
                <div class="search-result"></div>
            </div>
            {!! Form::close() !!}

            <div class="filtered">
                @foreach($shops as $shop)
                    <div class="item">
                        <button class="btn-delete" onclick="$(this).parent().remove()"></button>
                        <div class="image"><img src="{{ $shop->image ? asset('/storage/'.$shop->image) : asset('/storage/no_image.jpg') }}" alt=""></div>
                    </div>
                @endforeach
            </div>

            <div class="items">

                @forelse ($discounts as $discount)
                    <div class="action-item">
                        <button data-href="{{ route('admin.discounts.destroy', $discount->id) }}" class="btn-delete"></button>
                        <a href="{{ route('admin.discounts.edit', $discount->id) }}">
                            @foreach($discount->shops as $shop)
                            <div class="image"><img src="{{ $shop->image ? asset('/storage/'.$shop->image) : asset('/storage/no_image.jpg') }}" alt=""></div>
                            @endforeach
                        </a>

                        <div class="block">
                            <a href="{{ route('admin.discounts.edit', $discount->id) }}">
                            <span class="date">{{ Date::parse($discount->date_start)->format('d M') }} - {{ Date::parse($discount->date_end)->format('d M') }} </span>
                            <span class="title">{{ $discount->translate($app_locale)->title }}</span>
                            </a>
                        </div>
                        <div class="status">
                            <span>Активный</span>
                            <label class="checkbox">
                                <input data-href="{{route('admin.discounts.status', $discount->id)}}" type="checkbox" {!! $discount->status ? 'checked="checked" ' : '' !!}>
                                <input type="checkbox">
                                <span class="chk"></span>
                            </label>
                        </div>
                    </div>
                @empty

                @endforelse
            </div>

            <div class="pagination-row">
                <nav aria-label="Page navigation">
                    {{ $discounts->links() }}
                </nav>

                <div class="select">
                    <label>Выводить по</label>
                    {{ Form::select('status', [50 => 50, 100 => 100, 200 => 200], $limit, ['class' => 'custom-select', 'id' => 'limit']) }}
                 </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function addIdShop(idShop, imgShop){
        var addHtml = '<div class="item">\n' +
        '            <button class="btn-delete" onclick="$(this).parent().remove()"></button>\n' +
        '              <input type="hidden" class="shop-id" name="shop[]" value="'+idShop+'" >\n' +
        '            <div class="image"><img src="'+imgShop+'" alt=""></div>\n' +
        '        </div>';

        $('.filtered').append(addHtml);
    }
    $(function() {
        $("body").on('DOMSubtreeModified', ".filtered", function() {
            setTimeout(function () {
                var IDs = [];
                $(document).find('.shop-id').each(function () {
                    IDs.push($(this).val());
                });
                window.location.href = window.location.href.split('?')[0] + "?shops=" + IDs;
            }, 2000);
        });
    });
</script>

@endpush