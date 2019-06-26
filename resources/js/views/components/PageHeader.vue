<template>
  <header class="header">
    <div class="container">
      <div class="left">
        <router-link :to="{ name: 'Home' }" exact class="logo"></router-link>
        <router-link :to="{ name: 'Actions' }" class="nav-link">Акции</router-link>
      </div>

      <div class="search">
        <input type="text" placeholder="Поиск по товарам и магазинам" v-model.trim="search">
        <button type="button" class="btn btn-red">Найти</button>
      </div>

      <div class="righ">
        <button class="city-select" @click="showModalCity = true">{{city.name}}</button>

        <a href="/cutup-login" class="login"><span class="ico ico-login"></span> Войти</a>
      </div>


      <div class="search-result" v-if="resultOK">
        <span class="count">{{answer.count_actions}}</span> Акций
        <ul class="actions">
          <li v-for="action in answer.actions">
            <router-link :to="'/product/'+action.slug">
              <span class="image"><img :src="action.image" :alt="action.title"></span>
              <span class="title">{{action.title}}</span>
            </router-link>
          </li>
        </ul>
        <span class="count">{{answer.count_shops}}</span> Магазинов
        <ul class="shops">
          <li v-for="shop in answer.shops">
            <router-link :to="'/actions'+shop.slug">
              <span class="image"><img :src="shop.image" :alt="shop.title"></span>
            </router-link>
          </li>
        </ul>
      </div>

    </div>

    <modal v-if="showModalCity" @close="showModalCity = false" class="city-modal">
      <h3 slot="header">Ваш город {{city.name}}?</h3>
      <span slot="body">
        <input type="text" placeholder="Изменить город" v-model.trim="searchCity">
        <div class="cities">
          <span class="city" v-for="(city, id) in cities" @click="setCity(id, city)">{{city}}</span>
        </div>
      </span>
      <div slot="footer">
        <button class="btn btn-red" @click="showModalCity = false">Да</button>
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
          id: localStorage.cityId,
          name: localStorage.cityName
        },
        searchCity: '',
        cities: []
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

      setCity: function(id, city) {
        localStorage.cityId = id;
        localStorage.cityName = city;
        this.city.id = id;
        this.city.name = city;
        this.showModalCity = false;
        this.searchCity = '';
        this.cities = [];
        this.$root.$emit('cityChanged');
      }
    },

  }
</script>
