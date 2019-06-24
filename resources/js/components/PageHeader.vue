<template>
  <header class="header">
    <div class="container">
      <div class="left">
        <a href="/" class="logo"></a>
        <a href="#">Акции</a>
      </div>

      <div class="search">
        <input type="text" placeholder="Поиск по товарам и магазинам">
        <button type="button" class="btn btn-red">Найти</button>
      </div>

      <div class="righ">
        <div class="dropdown">Киев</div>

        <a href="#" class="login"><span class="ico ico-login"></span> Войти</a>
      </div>

    </div>
  </header>
</template>

<script>

  export default {
    name: 'PageHeader',
    data() {
      return {
      }
    },

  }
</script>
