<template>
  <header class="header">
    <div class="container">
      <div class="left">
        <router-link :to="{ name: 'Home' }" exact class="logo"></router-link>
        <router-link :to="{ name: 'Actions' }" class="nav-link">Акції</router-link>
      </div>

      <div class="search">
        <input type="text" placeholder="Поиск по товарам и магазинам" v-model.trim="search">
        <button type="button" class="btn btn-red">Знайти</button>
      </div>

      <div class="righ">
        <button class="city-select" @click="showModalCity = true">{{city.name}}</button>

        <a v-if="!userName" href="/login" class="login"><span class="ico ico-login"></span> Увійти</a>
        <a v-if="userName" href="/logout" @click.prevent="logout()" class="login"><span class="ico ico-login"></span> Вийти</a>
      </div>


      <div class="search-result" v-if="resultOK">
        <span class="count">{{answer.count_actions}}</span> Акцій
        <ul class="actions">
          <li v-for="action in answer.actions">
            <router-link :to="'/product/'+action.slug">
              <span class="image"><img :src="action.image" :alt="action.title"></span>
              <span class="title">{{action.title}}</span>
            </router-link>
          </li>
        </ul>
        <span class="count">{{answer.count_shops}}</span> Магазинів
        <ul class="shops">
          <li v-for="shop in answer.shops">
            <!-- <a href="/actions{{shop.slug}}"> -->
            <span class="image" @click="selectShop(shop.slug)"><img :src="shop.image" :alt="shop.title"></span>
            <!-- </a> -->
          </li>
        </ul>
      </div>
      <div class="search-result-bg" v-if="resultOK" @click="closeSearch"></div>

    </div>

    <modal v-if="showModalCity" @close="showModalCity = false" class="city-modal">
      <h3 slot="header">Ваше місто {{city.name}}?</h3>
      <span slot="body">
        <input type="text" placeholder="Змінити місто" v-model.trim="searchCity">
        <div class="cities">
          <span class="city" v-for="city in cities" @click="setCity(city)">{{city.name}}</span>
        </div>
      </span>
      <div slot="footer">
        <button class="btn btn-red" @click="showModalCity = false">Так</button>
      </div>
    </modal>

  </header>
</template>

<script>
  import axios from 'axios';
  import lodash from 'lodash';
  import Modal from '@/views/components/Modal';
  export default {
    name: 'PageHeader',
    components: {
      Modal
    },
    data() {
      return {
        showModalCity: false,
        search: '',
        resultOK: false,
        answer: [],
        city: {
          id: '',
          name: '',
          name2: ''
        },
        searchCity: '',
        cities: [],
        userName: false
      }
    },
    watch: { // эта функция запускается при любом изменении вопроса
      search: function () {this.debouncedGetSearch()},
      searchCity: function () {this.debouncedSearchCities()},
      '$route' (to, from) { //убирать модалки при переходе
        this.resultOK = false;
        this.search = '';
      }
    },
    created: function () { // _.debounce — это функция lodash, позволяющая ограничить то, насколько часто может выполняться определённая операция.
      this.debouncedGetSearch = _.debounce(this.getSearch, 500);
      this.debouncedSearchCities = _.debounce(this.searchCities, 500);
      this.resultOK = false;
      this.getUser();
    },
    mounted() {
      document.body.addEventListener('keyup', e => {
        if (e.keyCode === 27) { //Esc key event
          this.resultOK = false;
        }
      })

      if (!localStorage.cityId) {
        this.getCity();
        this.showModalCity = true;
      }
      this.city.id = localStorage.cityId;
      this.city.name = localStorage.cityName;
      this.city.name2 = localStorage.cityName2;
    },
    methods: {
      getSearch: function () {
        if (this.search.length < 3) return;

        axios.post('/api/actions-search?city='+localStorage.cityId, {data: this.search})
          .then(
            (response) => {
              this.answer = response.data;
              if (this.answer.status) this.resultOK = true;
            }
          )
          .catch(
            (error) => console.log(error)
          );
      },

      getCity: function() {
        axios.get('/api/city')
          .then(
            (response) => {
              this.city = response.data;
              localStorage.cityId = this.city.id;
              localStorage.cityName = this.city.name;
              localStorage.cityName2 = this.city.name2;
              this.$root.$emit('cityChanged');
            }
          )
          .catch(
            (error) => console.log(error)
          );
      },

      searchCities: function() {
        if (this.searchCity.length < 1) return;
        axios.get('/api/cities/'+this.searchCity)
          .then(
            (response) => {
              this.cities = response.data;
              // if (this.answer.status) this.resultOK = true;
            }
          )
          .catch(
            (error) => console.log(error)
          );
      },

      setCity: function(city) {
        localStorage.cityId = city.id;
        localStorage.cityName = city.name;
        localStorage.cityName2 = city.name2;
        this.city.id = city.id;
        this.city.name = city.name;
        this.city.name2 = city.name2;
        this.showModalCity = false;
        this.searchCity = '';
        this.cities = [];
        this.$root.$emit('cityChanged');
      },

      selectShop: function(slug) {
        // this.$router.push({ name: 'Actions', params: { shop: slug }});
        window.location.href = "/actions"+slug;
      },

      closeSearch: function() {
        this.resultOK = false;
      },

      getCookie: function(name) {
        var matches = document.cookie.match(new RegExp(
          "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
      },

      getUser: function() {
        var token = this.getCookie('user');
        if (!token) return false;
        axios.get('/api/user/'+token)
          .then(
            (response) => {
              this.userName = response.data.name;
              localStorage.userId = response.data.id;
              localStorage.userName = response.data.name;
              localStorage.userEmail = response.data.email;
            }
          )
          .catch(
            (error) => console.log(error)
          );
      },

      logout: function() {
        localStorage.clear();
        window.location.href = '/logout';
      }
    },

  }
</script>
