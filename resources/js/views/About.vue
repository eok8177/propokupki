<template>
  <main class="main about">
      <div class="about__block">
          <div class="wrapper about__wrapper" v-html="page.j_data.value"></div>
      </div>
      <div class="wrapper about__wrapper about__info about__mobile" v-html="page.description"></div>

  </main>
</template>

<script>
import axios from 'axios';
export default {
  name: 'About',
  data() {
    return {
      page: {
        description: null,
        j_data: {
          value: null
        }
      }
    }
  },
  metaInfo() {
    return {
      title: this.page.meta_title,
      meta: [
        { vmid: 'keywords', name: 'keywords', content: this.page.meta_keywords},
        { vmid: 'description', name: 'description', content: this.page.meta_description},
        { vmid: 'og:title', property: 'og:title', content: this.page.og_title},
        { vmid: 'og:description', property: 'og:description', content: this.page.og_description},
        { vmid: 'og:image', property: 'og:image', content: 'https://crown-cars.com/photos/shares/3.jpg'}
      ]
    }
  },
  created: function() {
    this.$parent.headerClass = 'about__header';
    axios.get('/api/page/o-nas')
      .then(
        (response) => {
          this.page = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
  },
  destroyed() {this.$parent.headerClass = false},
}
</script>