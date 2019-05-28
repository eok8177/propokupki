<template>
  <div class="home-page">

    <div class="container page-title">
      <h1 class="title">Акции и скидки Киева</h1>
      <p class="sub-title">230+ магазинов с лучшими предложениями</p>
    </div>

    <div class="slider">
      <div class="container">
        <div class="wrap">

          <div class="shop" v-for="shop in shops">
            <a href="#">
              <div class="image">
                <img :src="shop.image" :alt="shop.title">
              </div>
              <p><span class="count">{{shop.shops}}</span> Магазинов</p>
              <p><span class="count">{{shop.actions}}</span> Акций</p>
              <p class="discount">Скидки до <span class="number">{{shop.discount}}</span></p>
            </a>
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

    <div class="products">
      <div class="container">
        <h2 class="block-title">Лучшие акции Киева</h2>
        <div class="row">
          <div class="col-sm-6 col-md-3" v-for="action in actions">
            <div class="item">
              <div class="shop">
                <div class="image">
                  <img :src="action.shop.image" :alt="action.title">
                </div>
                <div class="right">
                  <div class="discount">Скидка <span>{{action.shop.discount}}</span></div>
                  <div class="dates">{{action.shop.discount}}</div>
                </div>
              </div>
              <div class="product">
                <hr>
                <div class="image">
                  <img :src="action.image" :alt="action.title">
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
              </div>
            </div>
          </div>
        </div>

        <div class="pagination-row">
          <button class="btn btn-red">Все акции</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Home',
  data() {
    return {
        shops: [],
        actions: [],
    }
  },
  created: function() {
    axios.get('/api/shops')
      .then(
        (response) => {
          this.shops = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
    axios.get('/api/actions')
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