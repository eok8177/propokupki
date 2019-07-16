<template>
  <div class="product-page">

    <div class="container page-title left">
      <router-link :to="{ name: 'Actions' }" class="btn">Акції та знижки {{cityName}}</router-link>
    </div>

    <div class="products">
      <div class="container">

        <div class="product-item">
          <div class="shop">
            <div class="image">
              <img :src="product.shop.image" :alt="product.title">
            </div>
            <div class="right">
              <div class="discount" v-if="product.shop.discount > 0">Знижка <span>-{{product.shop.discount}}%</span></div>
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
                  <span class="new">{{product.price}} <sup>грн</sup></span>
                  <span class="old">{{product.oldprice}}</span> <sup class="old-price">грн</sup>
                </div>
                <div v-if="product.count > 1" class="sticker">
                  Залишилось {{product.count}} днів
                </div>
                <div v-if="product.count == 1" class="sticker last">
                  Останній день
                </div>
              </div>
            </div>
            <hr>
          </div>
        </div>

        <h2 class="block-title">Схожі акції</h2>
      </div>
    </div>

    <products :products="actions"></products>


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
      cityName: '',
      meta: {
        title: 'Акції магазинів на сьогодні - ProPokupki',
        description: 'Акції та знижки всіх популярних магазинів України на сьогодні',
      }
    }
  },
  methods: {
    getContent (slug) {
      axios.get('/api/product/'+slug)
      .then(
        (response) => {
          this.product = response.data;
          this.meta.title = this.product.title +' '+ this.product.desc +' '+ this.product.tara +' ('+ this.product.shop.dates + ') - '+ this.product.shop.title + ' - ProPokupki';
          this.meta.description = 'Акції на "'+ this.product.title +' '+ this.product.desc +' '+ this.product.tara +'" в магазині '+ this.product.shop.title + ' ('+ this.product.shop.dates + ')';
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
    this.cityName = localStorage.cityName;
  },
  beforeRouteUpdate (to, from, next) {
    this.getContent(to.params.slug);
    next();
  },
  mounted() {
    this.$root.$on('cityChanged', () => {
      this.getContent(this.$route.params.slug);
      this.cityName = localStorage.cityName;
    })
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