@extends('backend.layouts.admin')

@section('content')

    <div class="container">
        <h1 class="title-page">Магазины <span class="badge badge-pill badge-secondary">{{ count($shops)  }}</span></h1>
    </div>
    <hr>

    <div class="shops">
        <div class="container">
            <div class="select">
                <select class="custom-select" name="" id="status">
                    <option {{ $status == 3 ?  'selected="selected"' : ''}} value="3">Все <span class="badge">({{ count($shops)  }})</span></option>
                    <option {{ $status == 1 ?  'selected="selected"' : ''}} value="1">Активные <span class="badge">({{ $count_on }})</span></option>
                    <option {{ $status == 0 ?  'selected="selected"' : ''}}value="0">Не активные <span class="badge">({{ $count_off }})</span></option>
                    <option {{ $status == 4 ?  'selected="selected"' : ''}}value="0">Без акций <span class="badge">({{ $not_discounts }})</span></option>
                </select>
            </div>

            <div class="search">
                <form action="#">
                    <input type="text" id="search" value="{{ $search }}" placeholder="Введите название">
                    <button class="btn-search"></button>
                </form>
            </div>

            <div class="items">
                <div class="row">
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="shop-item add">
                            <span class="ico ico-plus"></span>
                            <span class="title">Новый<br>магазин</span>
                            <hr>
                            <a href="{{ route('admin.shops.create') }}" class="btn btn-red">Добавить</a>
                        </div>
                    </div>
                    @forelse ($shops as $shop)
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                            <div class="shop-item">
                                <button data-href="{{ route('admin.shops.destroy', $shop->parent->id) }}" class="btn-delete"></button>
                                <div class="image"><img src="{{ $shop->parent->image ? asset('/storage/'.$shop->parent->image) : asset('/storage/no_image.jpg') }}" alt=""></div>
                                <a href="{{ route('admin.shops.edit', $shop->parent->id) }}"><span class="title">{{ $shop->title }}</span></a>
                                <span class="desc"></span>
                                <hr>
                                <div class="status">
                                    <span>Активный</span>
                                    <label class="checkbox">
                                        <input data-href="{{route('admin.shops.status', $shop->parent->id)}}" type="checkbox" {!! $shop->parent->status ? 'checked="checked" ' : '' !!}>
                                        <span class="chk"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>

            </div>

            <div class="pagination-row">
                <nav aria-label="Page navigation">
                    {{ $shops->links() }}
                </nav>

                <div class="select">
                    <label>Выводить по</label>
                    {{ Form::select('status', [50 => 50, 100 => 100, 200 => 200], $limit, ['class' => 'custom-select', 'id' => 'limit']) }}
                </div>

            </div>
        </div>
    </div>


@endsection