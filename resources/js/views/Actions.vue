<template>
  <div class="actions-page">

    <div class="container page-title left">
      <h1 class="title">Акції та знижки {{cityName2}}</h1>
    </div>

    <filter-top :shop="shop"></filter-top>

    <products :products="actions"></products>

  </div>
</template>

<script>
import FilterTop from '@/views/components/FilterTop';
import Products from '@/views/components/Products';

export default {
  name: 'Actions',
  components: {
    Products,
    FilterTop
  },
  data() {
    return {
      filter: '',
      actions: [],
      shop: '',
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
      this.meta.title = 'Акції магазинів на сьогодні - ProPokupki';
      this.meta.description = 'Акції та знижки всіх популярних магазинів України на сьогодні';
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