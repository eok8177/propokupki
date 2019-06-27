<template>
  <footer class="footer">
    <div class="container">
      <div class="first-line">
        <div class="left-menu">
          <router-link :to="{ name: 'Home' }" exact class="nav-link">Головна</router-link>
          <router-link :to="{ name: 'Actions' }" class="nav-link">Акції</router-link>
          <router-link :to="{ name: 'About' }" class="nav-link">Про проект</router-link>
          <router-link :to="{ name: 'Cabinet' }" class="nav-link">Особистий кабінет</router-link>
          <router-link :to="{ name: 'Contact' }" class="nav-link">Допомога</router-link>
        </div>
        <div class="socials">
          <p>Слідкуй за нами</p>
          <a href="#" class="ico ico-fb"></a>
          <a href="#" class="ico ico-gp"></a>
        </div>
      </div>

      <div class="copyright">
        © 2019 ProPokupki — Акції та знижки України
      </div>
    </div>
  </footer>
</template>

<script>

  export default {
    name: 'PageFooter'
  }
</script>
