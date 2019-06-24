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
                  <div class="dates">{{action.shop.dates}}</div>
                </div>
              </div>
              <div class="product">
                <router-link :to="{ name: 'Product', params: {slug: action.slug} }" exact>
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
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <div v-if="homePage" class="pagination-row">
          <router-link :to="{ name: 'Actions' }" class="btn btn-red">Все акции</router-link>
        </div>

        <div v-if="!homePage" class="pagination-row">
          <button @click="clickMore()" class="btn btn-red">Загрузить</button>

          <paginate
            :page-count="pages"
            :page-range="3"
            :margin-pages="1"
            :click-handler="clickCallback"
            :prev-text="'Предыдущая'"
            :next-text="'Следующая'"
            :container-class="'pagination'"
            :page-class="'page-item'"
            :prev-class="'prev'"
            :next-class="'next'"
            >
          </paginate>
        </div>
      </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Products',
  props: ['products', 'pages', 'count', 'homePage'],
  data() {
    return {
        actions: this.products,
    }
  },
  watch: {
    products: function (newVal) {
      this.actions = newVal
    }
  },
  methods: {
    clickCallback: function (pageNum) {
      this.$parent.paginate({
        method: 'next',
        page: pageNum
      });
    },
    clickMore: function () {
      this.$parent.paginate({
        method: 'more',
        page: this.count + 12
      });
    }
  }
}
</script>