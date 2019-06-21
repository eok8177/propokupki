<template>
  <div class="actions-page">

    <div class="container page-title left">
      <h1 class="title">Акции и скидки Киева</h1>
    </div>

    <filter-top></filter-top>

    <products :products="actions" :pages="pages" :count="count"></products>

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
      pages: 10, //count pages in paginate
      count: 12, //count items on page
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
    },
    paginate: function(e) {
      console.log(e);
    }
  },
  created: function() {
    this.getActions(this.filter);
  },
}
</script>