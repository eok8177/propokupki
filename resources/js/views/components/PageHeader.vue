<template>
  <header class="header">
    <div class="container">
      <div class="left">
        <router-link :to="{ name: 'Home' }" exact class="logo"></router-link>
        <router-link :to="{ name: 'Actions' }" class="nav-link">Акции</router-link>
      </div>

      <div class="search">
        <input type="text" placeholder="Поиск по товарам и магазинам">
        <button type="button" class="btn btn-red">Найти</button>
      </div>

      <div class="righ">
        <button class="city-select" @click="showModalCity = true">Киев</button>

        <a href="/cutup-login" class="login"><span class="ico ico-login"></span> Войти</a>
      </div>

    </div>

    <modal v-if="showModalCity" @close="showModalCity = false">
      <h3 slot="header">Ваш город Киев?</h3>
      <span slot="body">modal body here</span>
      <div slot="footer">
        <button class="btn btn-red" @click="showModalCity = false">Да</button>
      </div>
    </modal>

  </header>
</template>

<script>
  import Modal from '@/views/components/Modal';
  export default {
    name: 'PageHeader',
    components: {
      Modal
    },
    data() {
      return {
        showModalCity: false
      }
    },

  }
</script>
