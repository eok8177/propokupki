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
                  <div class="discount">Знижка <span>-{{action.shop.discount}}%</span></div>
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
                    <span class="new">{{action.price}} <sup>грн</sup></span>
                    <span class="old">{{action.oldprice}}</span> <sup class="old-price">грн</sup>
                  </div>
                  <div v-if="action.count > 1" class="sticker">
                    Залишилось {{action.count}} днів
                  </div>
                  <div v-if="action.count == 1" class="sticker last">
                    Останній день
                  </div>
                </router-link>
              </div>
            </div>
          </div>

          <div v-if="actions.length < 1" class="sorry">Акцій не знайдено</div>
        </div>

        <div v-if="homePage" class="pagination-row">
          <router-link :to="{ name: 'Actions' }" class="btn btn-red">Усі акції</router-link>
        </div>

        <div v-if="!homePage" class="pagination-row">
          <button v-if="(this.products.length - this.firstCut - this.actions.length) > 0" @click="clickMore()" class="btn btn-red">Загрузити</button>

          <paginate
            v-if="(this.products.length - this.firstCut - this.actions.length) > 0 || pageNum > 1"
            v-model="pageNum"
            :page-count="pageCount"
            :page-range="3"
            :margin-pages="1"
            :click-handler="clickCallback"
            :prev-text="'Попередня'"
            :next-text="'Наступна'"
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
  var PAGE_COUNT = 28; // количество товаров на странице
import axios from 'axios';
export default {
  name: 'Products',
  props: ['products', 'homePage'],
  data() {
    return {
      actions: [],
      pageNum: 1, // текущая страница
      pageSize: PAGE_COUNT, // количество товаров на странице
      pageCount: 0, // количество страниц
      firstCut: 0, // количество товаров скрытых слева
    }
  },
  watch: {
    products: function (newVal) {
      this.pageNum = 1;
      this.firstCut = 0;
      this.pageSize = PAGE_COUNT;
      this.actions = this.paginate(newVal, this.pageSize, 1);
      this.pageCount = newVal.length / this.pageSize;
    }
  },
  methods: {
    clickCallback: function (pageNum) {
      this.firstCut = (pageNum - 1) * this.pageSize;
      this.actions = this.paginate(this.products, this.pageSize, this.pageNum);
    },
    clickMore: function () {
      this.pageSize += PAGE_COUNT;
      this.pageCount = this.products.length / this.pageSize;
      this.actions = this.products.slice(this.firstCut, this.pageSize + this.firstCut);
    },
    paginate: function(array, page_size, page_number) {
      --page_number; // because pages logically start with 1, but technically with 0
      return array.slice(page_number * page_size, (page_number + 1) * page_size);
    }

  }
}
</script>