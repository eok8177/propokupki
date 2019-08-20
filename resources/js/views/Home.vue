<template>
  <div class="home-page">

    <div class="container page-title">
      <h1 class="title">Акції та знижки {{cityName2}}</h1>
      <p class="sub-title">{{shopCount}}+ магазинів з кращими пропозиціями</p>
    </div>

    <div class="slider" :class="{'show-all-shops' : showAllShops}">
      <div class="container">
        <div class="wrap">

          <div class="shop" v-for="shop in shops">
            <router-link :to="{ name: 'Actions', query: {shop: shop.id} }">
              <div class="image">
                <img :src="shop.image" :alt="shop.title">
              </div>
              <p><span class="count">{{shop.shops}}</span> Магазинів</p>
              <p><span class="count">{{shop.products}}</span> Акцій</p>
              <p class="discount" v-if="shop.discount > 0">Знижки до <span class="number">-{{shop.discount}}%</span></p>
            </router-link>
          </div>

          <div class="shop" v-if="!showAllShops">
            <div class="ico ico-bag"></div>
            <p class="title">Більше {{shopCount}}</p>
            <p class="gray">Магазинів</p>
            <hr>

            <!-- <div class="dropdown"> -->
              <button class="btn btn-red" @click="viewAllShops()">
                Усі магазини
              </button>
            <!-- </div> -->
          </div>

        </div>
      </div>
    </div>

    <h2 class="block-title">Кращі акції {{cityName2}}</h2>
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
        cityName2: '',
        shopCount: '230',
        dropDowns: {
          shops: false,
        },
        showAllShops: false,
        meta: {
          title: 'ProPokupki - Акції та знижки України',
          description: 'Акції та знижки всіх популярних магазинів України в одному місці',
        }
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
      axios.get('/api/shops/?city='+localStorage.cityId+'&count=5')
        .then(
          (response) => {
            this.shops = response.data;
            this.cityName2 = localStorage.cityName2;
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

    selectShop: function(slug) {
      this.$router.push({ name: 'Actions', params: { shop: slug }});
      // window.location.href = "/actions"+slug;
    },

    toggle: function(name) {
       this.dropDowns[name] = !this.dropDowns[name];
    },

    viewAllShops: function() {
      axios.get('/api/shops/?city='+localStorage.cityId)
        .then(
          (response) => {
            this.shops = response.data;
            this.cityName2 = localStorage.cityName2;
            this.showAllShops = true;
          }
        )
        .catch(
          (error) => console.log(error)
        );
    },
  },

  metaInfo() {
    return {
      title: this.meta.title,
      meta: [
        { vmid: 'description', name: 'description', content: this.meta.description},
        { vmid: 'og:title', property: 'og:title', content: this.meta.title},
        { vmid: 'og:description', property: 'og:description', content: this.meta.description},
      ]
    }
  },
}
</script>