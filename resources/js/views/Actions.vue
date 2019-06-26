<template>
  <div class="actions-page">

    <div class="container page-title left">
      <h1 class="title">Акции и скидки Киева</h1>
    </div>

    <filter-top></filter-top>

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
  created: function() {
    this.getActions(this.filter);
  },
  mounted() {
    this.$root.$on('cityChanged', () => {
      this.getActions(this.filter);
    })
  },
}
</script>