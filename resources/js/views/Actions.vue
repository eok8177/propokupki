<template>
  <div class="actions-page">

    <div class="container page-title left">
      <h1 class="title">Акції та знижки {{cityName2}}</h1>
    </div>

    <filter-top :shop="shop" :search="search"></filter-top>

    <products :products="actions"></products>

    <action-text :shop="shop"></action-text>

  </div>
</template>

<script>
import FilterTop from '@/views/components/FilterTop';
import Products from '@/views/components/Products';
import ActionText from '@/views/components/ActionText';

export default {
  name: 'Actions',
  components: {
    Products,
    FilterTop,
    ActionText
  },
  data() {
    return {
      filter: {
        page: 1,
      },
      actions: [],
      shop: '',
      search: '',
      cityName2: '',
      meta: {
        title: 'Акції магазинів на сьогодні - ProPokupki',
        description: 'Акції та знижки всіх популярних магазинів України на сьогодні',
      }
    }
  },
  methods: {
    filtered: function(filter) {
      this.getActions(filter);
      if (filter.shops.length > 1 || filter.shops.length == 0) {
        this.meta.title = 'Акції магазинів на сьогодні - ProPokupki';
        this.meta.description = 'Акції та знижки всіх популярних магазинів України на сьогодні';
      } else if (filter.shops.length == 1) {
        this.getShop(filter.shops);
      }
    },
    getActions: function(filter) {
      axios.get('/api/actions?city='+localStorage.cityId,{params: filter})
        .then(
          (response) => {
            this.actions = response.data;
          }
        )
        .catch(
          (error) => console.log(error)
        );
    },
    getShop: function (shop) {
      axios.get('/api/shop/'+shop)
        .then(
          (res) => {
            this.meta.title = 'Акції ' + res.data.title + ' на сьогодні - ProPokupki';
            this.meta.description = 'Акції та знижки магазинів ' + res.data.title + ' на сьогодні';
          }
        )
        .catch(
          (error) => console.log(error)
        );
    },
    moreData () {
      axios.get('/api/actions?city='+localStorage.cityId,{params: this.filter})
      .then(
        (response) => {
          this.actions.data = this.actions.data.concat(response.data.data);
        }
      )
      .catch(
        (error) => console.log(error)
      );
    }
  },
  mounted() {
    this.$root.$on('cityChanged', () => {
      this.getActions(this.filter);
      this.cityName2 = localStorage.cityName2;
    });
    if(this.$route.query.shop) {
        this.shop = this.$route.query.shop;
        this.getShop(this.shop);
    }
    if(this.$route.query.search) {
        this.search = this.$route.query.search;
    }
    this.cityName2 = localStorage.cityName2;
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