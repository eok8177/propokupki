<template>
    <div class="products">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-md-4 col-lg-3" v-for="action in actions">
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
</template>

<script>
import axios from 'axios';
export default {
  name: 'Products',
  data() {
    return {
        actions: [],
    }
  },
  created: function() {
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