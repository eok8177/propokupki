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
                <select class="custom-select" name="status" onchange="filter(); return false;">
                    <option value="all" {{ request('status') === 'all' ? 'selected="selected"' : ''}}>Все <span class="badge">{{ $header_discounts }}</span></option>
                    <option value="on" {{ request('status') === 'on' ? 'selected="selected"' : ''}}>Активные <span class="badge">{{ $count_on }}</span></option>
                    <option value="off" {{ request('status') === 'off' ? 'selected="selected"' : ''}}>не активные <span class="badge">{{ $count_off }}</span></option>
                </select>
            </div>


            <div class="search">
                <input name="search" id="shop_search" type="text" data-href="{{route('admin.shops.ajaxShops')}}" placeholder="Введите название">
                <button type="button" class="btn-search" onclick="filter(); return false;"></button>
                <div class="search-result"></div>
            </div>


            <div class="filtered">
                @foreach($shops as $shop)
                    <div class="item" data-id="{{ $shop->id }}">
                        <button class="btn-delete" onclick="removeIdShop({{ $shop->id }}); return false;"></button>
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
                    {{ $discounts->appends(request()->all())->links() }}
                </nav>

                <div class="select">
                    <label>Выводить по</label>
                    {{ Form::select('limit', [50 => 50, 100 => 100, 200 => 200], request('limit', 50), ['class' => 'custom-select', 'onchange' => 'filter(); return false;']) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let shops = [];

    function init() {
        if ($('.filtered > .item').length > 0) {
            $('.filtered > .item').each(function () {
               shops.push($(this).data('id'));
            });
        }
    }

    function addIdShop(idShop, imgShop){
        if (shops.indexOf(idShop) < 0) {
            shops.push(idShop);

            let html = '<div class="item" data-id="' + idShop + '">\n' +
                '            <button class="btn-delete" onclick="removeIdShop(' + idShop + '); return false;"></button>\n' +
                '            <div class="image"><img src="' + imgShop + '" alt=""></div>\n' +
                '        </div>';

            $('.filtered').append(html);
        }
    }

    function removeIdShop(idShop) {
        shops.splice(shops.indexOf(idShop), 1);
        $('.item[data-id="' + idShop + '"]').remove();
    }

    function filter() {
        let params = {
            shops: shops.join(','),
            status: $('select[name="status"]').val(),
            limit: $('select[name="limit"]').val(),
            page: {{ request('page', 1) }}
        };

        let url = window.location.href.split('?')[0];

        for (i in params) {
            url += (i === 'shops' ? '?' : '&') + [i, params[i]].join('=');
        }

        window.location = url;
    }

    init();
</script>

@endpush