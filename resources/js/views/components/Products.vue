<template>
    <div class="products">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-md-4 col-lg-3" v-for="action in actions.data">
            <div class="item" :class="classProduct">
              <div class="shop">
                <div class="image">
                  <img :src="action.shop.image" :alt="action.shop.title">
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
                    <span class="new"><span v-if="action.of" style="font-size: 12px;"> від </span> {{action.price}} <sup>грн</sup></span>
                    <span class="old" v-if="action.oldprice > 0">{{action.oldprice}}</span> <sup class="old-price" v-if="action.oldprice > 0">грн</sup>
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

          <div v-if="empty" class="sorry">Акцій не знайдено</div>
        </div>

        <div v-if="homePage" class="pagination-row">
          <router-link :to="{ name: 'Actions' }" class="btn btn-red">Усі акції</router-link>
        </div>

        <div v-if="!homePage" class="pagination-row">
          <button v-if="(this.pageCount - this.pageNum) > 0" @click="clickMore()" class="btn btn-red">Завантажити</button>

          <paginate
            v-if="this.pageCount > 1"
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
import axios from 'axios';
export default {
  name: 'Products',
  props: ['products', 'homePage'],
  data() {
    return {
      actions: {
        data: [
          {
            shop: {
              image: '',
              title: '',
            }
          },
          {
            shop: {
              image: '',
              title: '',
            }
          },
          {
            shop: {
              image: '',
              title: '',
            }
          },
          {
            shop: {
              image: '',
              title: '',
            }
          },
        ],
      },
      classProduct: 'loading',
      pageNum: 1, // текущая страница
      pageCount: 0, // количество страниц
      empty: false, // нет товаров
    }
  },
  watch: {
    products: function (newVal) {
      this.pageNum = newVal.current_page;
      this.pageCount = newVal.last_page;
      this.actions = newVal;
      this.classProduct = '';
      if (newVal.total == 0)
        this.empty = true;
    }
  },
  methods: {
    clickCallback: function (pageNum) {
      this.$parent.filter.page = pageNum;
      this.$parent.getActions(this.$parent.filter);
    },
    clickMore: function () {
      this.pageNum++;
      this.$parent.filter.page = this.pageNum;
      this.$parent.moreData();
    },

  }
}
</script>