<template>
  <div class="home-page">

    <div class="container page-title">
      <h1 class="title">Акции и скидки Киева</h1>
      <p class="sub-title">230+ магазинов с лучшими предложениями</p>
    </div>

    <div class="slider">
      <div class="container">
        <div class="wrap">

          <div class="shop" v-for="shop in shops.slice(0, 5)">
            <router-link :to="{ name: 'Product', params: {slug: shop.slug} }" exact>
              <div class="image">
                <img :src="shop.image" :alt="shop.title">
              </div>
              <p><span class="count">{{shop.shops}}</span> Магазинов</p>
              <p><span class="count">{{shop.actions}}</span> Акций</p>
              <p class="discount">Скидки до <span class="number">{{shop.discount}}</span></p>
            </router-link>
          </div>

          <div class="shop">
            <div class="ico ico-bag"></div>
            <p class="title">Более 230</p>
            <p class="gray">Магазинов</p>
            <hr>
            <button class="btn btn-red">Все магазины</button>
          </div>

        </div>
      </div>
    </div>

    <h2 class="block-title">Лучшие акции Киева</h2>
    <products :products="actions"></products>

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
    }
  },
  created: function() {
    axios.get('/api/shops/?city='+localStorage.cityId)
      .then(
        (response) => {
          this.shops = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
      axios.get('/api/actions/?city='+localStorage.cityId)
        .then(
          (response) => {
            this.actions = response.data;
          }
        )
        .catch(
          (error) => console.log(error)
        );
  },
}
</script>