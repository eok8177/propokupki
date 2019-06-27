<template>
  <div class="product-page">

    <div class="container page-title left">
      <h1 class="title">Карточка товара</h1>
    </div>

    <div class="products">
      <div class="container">

        <div class="product-item">
          <div class="shop">
            <div class="image">
              <img :src="product.shop.image" :alt="product.title">
            </div>
            <div class="right">
              <div class="discount">Скидка <span>{{product.shop.discount}}</span></div>
              <div class="dates">{{product.shop.dates}}</div>
            </div>
          </div>
          <div class="product">
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <img :src="product.image" :alt="product.title">
              </div>
              <div class="col-sm-8">
                <span class="title">{{product.title}}</span>
                <span class="desc">{{product.desc}}</span>
                <span class="tara">{{product.tara}}</span>
                <hr>
                <div class="prices">
                  <span class="new">{{product.price}}</span>
                  <span class="old">{{product.oldprice}}</span>
                </div>
                <div v-if="product.count > 1" class="sticker">
                  Осталось {{product.count}} дней
                </div>
                <div v-if="product.count == 1" class="sticker last">
                  Последний день
                </div>
              </div>
            </div>
            <hr>
          </div>
        </div>

        <h2 class="block-title">Похожие акции</h2>
      </div>
    </div>

    <products :products="actions" homePage="true"></products>


  </div>
</template>

<script>
import axios from 'axios';
import Products from '@/views/components/Products';
export default {
  name: 'Product',
  components: {
    Products
  },
  data() {
    return {
      product: {
        'slug': '',
        'title': '',
        'image': '',
        'desc': '',
        'price': '',
        'oldprice': '',
        'count': '',
        'shop': {
          'image': '',
          'dates': '',
          'discount': ''
        }
      },
      actions: [],
    }
  },
  methods: {
    getContent (slug) {
      axios.get('/api/product/'+slug)
      .then(
        (response) => {
          this.product = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
      axios.get('/api/product-related/'+slug+'/?city='+localStorage.cityId)
      .then(
        (response) => {
          this.actions = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
    }
  },
  created: function() {
    this.getContent(this.$route.params.slug);
  },
  beforeRouteUpdate (to, from, next) {
    this.getContent(to.params.slug);
    next();
  }
}
</script>