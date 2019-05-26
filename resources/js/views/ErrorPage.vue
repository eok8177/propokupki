<template>
  <div class="page-404">
    <div class="container">
      <h1>404</h1>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'ErrorPage'
  }
</script>

<style scoped lang=scss>
.page-404 {
  .container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: calc(100vh - 200px);
    h1 {
      font-size: 20vw;
      color: #ef6161;
    }
  }
}
</style>