<template>
  <div class="actions-page">

    <div class="container page-title left">
      <h1 class="title">Акції та знижки {{cityName}}</h1>
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
      cityName: '',
    }
  },
  methods: {
    filtered: function(filter) {
      this.getActions(filter);
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
    }
  },
  mounted() {
    this.$root.$on('cityChanged', () => {
      this.getActions(this.filter);
    });
    if(this.$route.query.shop) {
        this.shop = this.$route.query.shop;
    }
    this.cityName = localStorage.cityName;
  },
}
</script>