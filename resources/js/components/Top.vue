<template>
  <header class="header main-header page-header">
    <div class="wrapper ">
      <div class="header__wrapper">
        <router-link to="/" class="header__logo">
          <img src="/img/logo-header.svg" alt="Logo">
        </router-link>
        <button class="open">Open</button>
        <div class="header__block">
          <ul class="header__tels">
            <li class="header__tels-item"><a href="#" class="header__tels-link">(044) 362 78 38</a></li>
            <li class="header__tels-item"><a href="#" class="header__tels-link">(067) 558 88 38</a></li>
            <li class="header__tels-item"><a href="#" class="header__tels-link">(093) 518 88 38</a></li>
            <li class="header__tels-item"><a href="#" class="header__tels-link">(050) 458 88 38</a></li>
            <a href="#" class="navigation__hidden button">Заказать звонок</a>
          </ul>
          <nav class="header__navigation">
            <router-link to="/" exact class="navigation__item" active-class="navigation__item--active">Главная</router-link>
            <router-link to="/avtopark" class="navigation__item" active-class="navigation__item--active">Автопарк</router-link>
            <router-link to="/about-us" class="navigation__item" active-class="navigation__item--active">О нас</router-link>
            <router-link to="/reviews" class="navigation__item" active-class="navigation__item--active">Отзывы</router-link>
            <router-link to="/tips" class="navigation__item" active-class="navigation__item--active">Советы</router-link>
            <a href="#" class="navigation__call button button-white">Заказать звонок</a>
          </nav>
        </div>
      </div>

      <ul class="main__nav">
        <li class="main__nav-item"><a href="#" class="main__nav-link main__nav--active">Грузоперевозки</a></li>
        <div class="mobile_wrap">
          <li class="main__nav-item nav-link"><a href="#" class="main__nav-link">Квартирный переезд</a></li>
          <li class="main__nav-item nav-link"><a href="#" class="main__nav-link">Офисный переезд</a></li>
          <li class="main__nav-item nav-link"><a href="#" class="main__nav-link">Грузовое такси</a></li>
          <li class="main__nav-item nav-link"><a href="#" class="main__nav-link">Грузовое такси</a></li>
          <li class="main__nav-item nav-link"><a href="#" class="main__nav-link ">Пассажирские перевозки</a></li>
          <li class="main__nav-item nav-link"><a href="#" class="main__nav-link ">Вывоз строймусора</a></li>
        </div>
      </ul>

    </div>
  </header>
</template>

<script>

  export default {
    name: 'Top'
  }
</script>