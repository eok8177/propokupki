<template>
  <header class="header main-header">
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
      <div class="header__bus">
        <template v-for="bus in buses">
          <router-link :to="'/bus/'+bus.id" class="header__bus-link">
            <img :src="bus.img" alt="bus" class="header__bus-img">
            <span class="header__bus-title">{{bus.title}}</span>
          </router-link>
        </template>
      </div>
      <div class="header__sticker">
        <h2 class="header__sticker-title mobile-non">Грузоперевозки</h2>
        <span class="header__sticker-price mobile-non">400 грн/год.</span>
        <a href="#" class="button">Заказать звонок</a>
      </div>
    </div>
  </header>
</template>

<script>

  export default {
    name: 'TopHome',
    data() {
      return {
        buses: [
          {id:'1',title: '1,5 тонны', img:'img/bus-small.png'},
          {id:'2',title: '2 тонны', img:'img/bus-small.png'},
          {id:'3',title: '3-тонник', img:'img/bus-small.png'},
          {id:'4',title: '5-тонник', img:'img/bus-small.png'},
          {id:'5',title: '10-тонник', img:'img/bus-small.png'},
          {id:'6',title: '20-тонник', img:'img/bus-small.png'}
        ]
      }
    }
  }
</script>