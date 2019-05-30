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
        <button class="city-select" @click="showModalCity = true">Киев</button>

        <a href="/cutup-login" class="login"><span class="ico ico-login"></span> Войти</a>
      </div>


      <div class="search-result" v-if="resultOK">
        <span class="count">{{answer.count_actions}}</span> Акций
        <ul class="actions">
          <li v-for="action in answer.actions">
            <router-link :to="'/action/'+action.url">
              <span class="image"><img :src="action.image" :alt="action.title"></span>
              <span class="title">{{action.title}}</span>
            </router-link>
          </li>
        </ul>
        <span class="count">{{answer.count_shops}}</span> Магазинов
        <ul class="shops">
          <li v-for="shop in answer.shops">
            <router-link :to="'/shop/'+shop.url">
              <span class="image"><img :src="shop.image" :alt="shop.title"></span>
            </router-link>
          </li>
        </ul>
      </div>

    </div>

    <modal v-if="showModalCity" @close="showModalCity = false">
      <h3 slot="header">Ваш город Киев?</h3>
      <span slot="body">modal body here</span>
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
        answer: []
      }
    },
    watch: { // эта функция запускается при любом изменении вопроса
      search: function (newQuestion, oldQuestion) {
        this.debouncedGetSearch()
      },
      '$route' (to, from) {
        console.log('watch');
        this.resultOK = false;
        this.search = '';
      }
    },
    created: function () { // _.debounce — это функция lodash, позволяющая ограничить то, насколько часто может выполняться определённая операция.
      this.debouncedGetSearch = _.debounce(this.getSearch, 500);
      this.resultOK = false;
    },
    mounted() {
      document.body.addEventListener('keyup', e => {
        if (e.keyCode === 27) {
          this.resultOK = false;
        }
      })
    },
    methods: {
      getSearch: function () {
        if (this.search.length < 3) return;

        axios.post('/api/actions', {data: this.search})
          .then(
            (response) => {
              this.answer = response.data;
              if (this.answer.status) this.resultOK = true;
            }
          )
          .catch(
            (error) => console.log(error)
          );
      }
    },

  }
</script>
