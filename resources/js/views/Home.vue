<template>
  <div class="home-page">

    <div class="container page-title">
      <h1 class="title">Акції та знижки {{cityName}}</h1>
      <p class="sub-title">{{shopCount}}+ магазинів з кращими пропозиціями</p>
    </div>

    <div class="slider">
      <div class="container">
        <div class="wrap">

          <div class="shop" v-for="shop in shops">
            <router-link :to="{ name: 'Actions', query: {shop: shop.id} }">
              <div class="image">
                <img :src="shop.image" :alt="shop.title">
              </div>
              <p><span class="count">{{shop.shops}}</span> Магазинів</p>
              <p><span class="count">{{shop.actions}}</span> Акцій</p>
              <p class="discount" v-if="shop.discount > 0">Знижки до <span class="number">{{shop.discount}}%</span></p>
            </router-link>
          </div>

          <div class="shop">
            <div class="ico ico-bag"></div>
            <p class="title">Більше {{shopCount}}</p>
            <p class="gray">Магазинів</p>
            <hr>
            <button class="btn btn-red">Усі магазини</button>
          </div>

        </div>
      </div>
    </div>

    <h2 class="block-title">Кращі акції {{cityName}}</h2>
    <products :products="actions" homePage="true"></products>

  </div>
</template>

<script>
import axios from 'axios';
import Products from '@/views/components/Products';

export default {
  name: 'Home',
  components: {
    Products
  },
  data() {
    return {
        shops: [],
        actions: [],
        cityName: '',
        shopCount: '230',
    }
  },
  created: function() {
    this.getAll();
  },
  mounted() {
    this.$root.$on('cityChanged', () => {
      this.getAll();
    })
  },
  methods: {
    getAll: function() {
      axios.get('/api/shops/?city='+localStorage.cityId)
        .then(
          (response) => {
            this.shops = response.data;
            this.cityName = localStorage.cityName;
          }
        )
        .catch(
          (error) => console.log(error)
        );
        axios.get('/api/actions/?city='+localStorage.cityId)
          .then(
            (response) => {
              this.actions = response.data.slice(0, 5);
            }
          )
          .catch(
            (error) => console.log(error)
          );
    },

    selectShop: function(slug) {
      this.$router.push({ name: 'Actions', params: { shop: slug }});
      // window.location.href = "/actions"+slug;
    },
  }
}
</script>