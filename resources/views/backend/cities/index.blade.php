@extends('backend.layouts.admin')

@section('content')

    <div class="container">
        <h1 class="title-page">Города <span class="badge badge-pill badge-secondary">238</span></h1>
    </div>
    <hr>

    <div class="shops">
        <div class="container">
            <div class="select">
                <select class="custom-select" name="" id="">
                    <option selected="selected">Активные <span class="badge">{{ count($cities)  }}</span></option>
                    <option >не активные <span class="badge">40</span></option>
                </select>
            </div>

            <div class="search">
                <input type="text" placeholder="Введите название">
                <button class="btn-search"></button>
            </div>

            <div class="items">
                <div class="row">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($cities as $city)
                            <tr>
                                <th scope="row">{{ $city->id }}</th>
                                <td>{{ $city->translate($app_locale)->title }}</td>
                                <td>{{ $city->status }}</td>
                            </tr>
                        @empty

                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="pagination-row">
                <nav aria-label="Page navigation">
                    {{ $cities->links() }}
                </nav>

                <div class="select">
                    <label>Выводить по</label>
                    <select class="custom-select">
                        <option value="">50</option>
                        <option value="">100</option>
                        <option value="">200</option>
                    </select>
                </div>

            </div>
        </div>
    </div>


@endsection