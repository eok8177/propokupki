@extends('backend.layouts.admin')

@section('content')

    <div class="container">
        <h1 class="title-page">Города <span class="badge badge-pill badge-secondary">{{ count($shops)  }}</span></h1>
    </div>
    <hr>

    <div class="shops">
        <div class="container">
            <div class="select">
                <select class="custom-select" name="" id="">
                    <option selected="selected" value="1">Активные <span class="badge">{{ $count_on }}</span></option>
                    <option value="0">не активные <span class="badge">{{ $count_off }}</span></option>
                </select>
            </div>

            <div class="search">
                <input type="text" placeholder="Введите название">
                <button class="btn-search"></button>
            </div>

            <div class="items">
                <div class="row">
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="shop-item add">
                            <span class="ico ico-plus"></span>
                            <span class="title">Новый<br>город</span>
                            <hr>
                            <a href="{{ route('admin.shops.create') }}" class="btn btn-red">Добавить</a>
                        </div>
                    </div>
                    @forelse ($shops as $shop)
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                            <div class="shop-item">
                                <button data-href="{{ route('admin.shops.destroy', $shop->parent->id) }}" class="btn-delete"></button>
                                <div class="image"><img src="{{ asset('/storage/'.$shop->parent->image) }}" alt=""></div>
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
{{--                    <ul class="pagination">--}}
{{--                        <li class="page-item disabled"><span class="page-link">«</span></li>--}}
{{--                        <li class="page-item active"><span class="page-link">1</span></li>--}}
{{--                        <li class="page-item"><a class="page-link" href="?page=2">2</a></li>--}}
{{--                        <li class="page-item"><a class="page-link" href="?page=3">3</a></li>--}}
{{--                        <li class="page-item"><a class="page-link" href="?page=4">4</a></li>--}}

{{--                        <li class="page-item"><a class="page-link" href="?page=11">11</a></li>--}}
{{--                        <li class="page-item"><a class="page-link" href="?page=2" aria-label="Next">Следующая</a></li>--}}
{{--                    </ul>--}}
                </nav>

                <div class="select">
                    <label>Выводить по</label>
                    <select class="custom-select">
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>
                </div>

            </div>
        </div>
    </div>


@endsection