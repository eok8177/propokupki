<template>
  <div class="actions-page">

    <div class="container page-title left">
      <h1 class="title">Акции и скидки Киева</h1>
    </div>

    <products></products>

  </div>
</template>

<script>
import Products from '@/views/components/Products';

export default {
  name: 'Actions',
  components: {
    Products
  }
}
</script>