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
              <img :src="'/'+product.shop.image" :alt="product.title">
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
                <img :src="'/'+product.image" :alt="product.title">
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

        <h1 class="block-title">Похожие акции</h1>

        <div class="row">
          <div class="col-sm-6 col-md-4 col-lg-3" v-for="action in actions">
            <div class="item">
              <div class="shop">
                <div class="image">
                  <img :src="'/'+action.shop.image" :alt="action.title">
                </div>
                <div class="right">
                  <div class="discount">Скидка <span>{{action.shop.discount}}</span></div>
                  <div class="dates">{{action.shop.dates}}</div>
                </div>
              </div>
              <div class="product">
                <router-link :to="{ name: 'Product', params: {slug: action.slug} }" exact class="logo">
                  <hr>
                  <div class="image">
                    <img :src="'/'+action.image" :alt="action.title">
                  </div>
                  <span class="title">{{action.title}}</span>
                  <span class="desc">{{action.desc}}</span>
                  <span class="tara">{{action.tara}}</span>
                  <hr>
                  <div class="prices">
                    <span class="new">{{action.price}}</span>
                    <span class="old">{{action.oldprice}}</span>
                  </div>
                  <div v-if="action.count > 1" class="sticker">
                    Осталось {{action.count}} дней
                  </div>
                  <div v-if="action.count == 1" class="sticker last">
                    Последний день
                  </div>
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <div class="pagination-row">
          <button class="btn btn-red">Загрузить еще</button>
        </div>
      </div>
    </div>


  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Product',
  components: {
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
  created: function() {
    axios.get('/api/product/slug')
    .then(
      (response) => {
        this.product = response.data;
      }
    )
    .catch(
      (error) => console.log(error)
    );

    axios.get('/api/product-related/slug')
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