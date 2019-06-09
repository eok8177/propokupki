@extends('backend.layouts.admin')

@section('content')

    <div class="container">
        <h1 class="title-page">Акции <span class="badge badge-pill badge-secondary">238</span></h1>
        <a href="{{ route('admin.discounts.create') }}" class="btn-noborder"><span class="ico ico-big ico-plus"></span> Новая акция</a>
    </div>
    <hr>

    <div class="actions">
        <div class="container">
            <div class="select">
                <select name="" class="custom-select" name="" id="status">
                    <option value="1" {{$status == 1 ? 'selected="selected"' : ''}}>Активные <span class="badge">{{ $count_on }}</span></option>
                    <option value="0" {{$status == 0 ? 'selected="selected"' : ''}}>не активные <span class="badge">{{ $count_off }}</span></option>
                </select>
            </div>

            {!! Form::open(['route' => ['admin.discounts.index'], 'method' => 'GET']) !!}
            <div class="search">
                <input name="search" type="text" placeholder="Введите название" value="{{app('request')->input('search')}}">
                <button type="submit" class="btn-search"></button>
            </div>
            {!! Form::close() !!}

            <div class="filtered">
                <div class="item">
                    <button class="btn-delete"></button>
                    <div class="image"><img src="/images/shop-1.jpg" alt=""></div>
                </div>
                <div class="item">
                    <button class="btn-delete"></button>
                    <div class="image"><img src="/images/shop-1.jpg" alt=""></div>
                </div>
                <div class="item">
                    <button class="btn-delete"></button>
                    <div class="image"><img src="/images/shop-1.jpg" alt=""></div>
                </div>
            </div>

            <div class="items">

                @forelse ($discounts as $discount)
                    <div class="action-item">
                        <button data-href="{{ route('admin.discounts.destroy', $discount->parent->id) }}" class="btn-delete"></button>
                        <a href="{{ route('admin.discounts.edit', $discount->parent->id) }}">
                            <div class="image"><img src="{{ $discount->parent->image ? asset('/storage/'.$discount->parent->image) : asset('/storage/no_image.jpg') }}" alt=""></div>
                        </a>

                        <div class="block">
                            <a href="{{ route('admin.discounts.edit', $discount->parent->id) }}">
                            <span class="date">14–27 марта </span>
                            <span class="title">{{ $discount->title }}</span>
                            </a>
                        </div>
                        <div class="status">
                            <span>Активный</span>
                            <label class="checkbox">
                                <input data-href="{{route('admin.shops.status', $discount->parent->id)}}" type="checkbox" {!! $discount->parent->status ? 'checked="checked" ' : '' !!}>
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