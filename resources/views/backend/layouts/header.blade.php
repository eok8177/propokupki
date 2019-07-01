{{-- Navigation --}}
<nav class="navbar navbar-expand-md">
    <div class="container">
        <div class="left-nav">
            <a class="logo" href="/" target="_blank"><img src="{{ asset('img/admin/logo.svg') }}" alt=""></a>
            <a href="/admin/shops" class="{{ Request::is('admin/shops*') ? 'active' : '' }}">Магазины <span class="badge badge-pill badge-warning">{{ $header_shops }}</span></a>
            <a href="/admin/discounts" class="{{ Request::is('admin/discounts*') ? 'active' : '' }}">Акции <span class="badge badge-pill badge-warning">{{ $header_discounts }}</span></a>
        </div>

        <div class="right-nav dropdown">
            <a href="#" class="dropdown-toggle" id="userMenu" data-toggle="dropdown">Margarita</a>
            <div class="dropdown-menu" aria-labelledby="userMenu">
                <hr>
                <a href="#"><i class="ico ico-user"></i> Профиль</a>
                <a href="#"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="ico ico-logout"></i> Выйти</a>

                <form id="logout-form" action="#" method="POST" style="display: none;">{{ csrf_field() }}</form>
            </div>
        </div>

    </div>
</nav>

{{--  PAGE  --}}
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has($msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
        @endif
    @endforeach

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif
</div>