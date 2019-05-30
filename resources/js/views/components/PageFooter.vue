<template>
  <footer class="footer">
    <div class="container">
      <div class="first-line">
        <div class="left-menu">
          <router-link :to="{ name: 'Home' }" exact class="nav-link">Главная</router-link>
          <router-link :to="{ name: 'Actions' }" class="nav-link">Акции</router-link>
          <a href="#">О проекте</a>
          <a href="#">Личный кабинет</a>
        </div>
        <div class="socials">
          <p>Следи за нами</p>
          <a href="#" class="ico ico-fb"></a>
          <a href="#" class="ico ico-gp"></a>
        </div>
      </div>

      <div class="copyright">
        © 2019 ProPokupki — Акции и скидки Украины
      </div>
    </div>
  </footer>
</template>

<script>

  export default {
    name: 'PageFooter'
  }
</script>
