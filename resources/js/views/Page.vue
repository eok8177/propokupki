<template>
  <div>
    <info :pageVal="pageVal"></info>
    <advantages></advantages>
    <reviews></reviews>
  </div>
</template>

<script>
import axios from 'axios';
import Top from '@/components/TopHome'
import Info from '@/components/Info'
import Advantages from '@/components/home/Advantages'
import Reviews from '@/components/home/Reviews'

export default {
  name: 'Page',
  components: {
    Top,
    Info,
    Advantages,
    Reviews
  },
  created() {this.$parent.home = true},
  mounted() {this.$parent.home = true},
  destroyed() {this.$parent.home = false},
  data() {
    return {
        pageVal: {
          j_data: {
            price: []
          }
        }
    }
  },
  metaInfo() {
    return {
      title: this.pageVal.meta_title,
      meta: [
        { vmid: 'keywords', name: 'keywords', content: this.pageVal.meta_keywords},
        { vmid: 'description', name: 'description', content: this.pageVal.meta_description},
        { vmid: 'og:title', property: 'og:title', content: this.pageVal.og_title},
        { vmid: 'og:description', property: 'og:description', content: this.pageVal.og_description},
        { vmid: 'og:image', property: 'og:image', content: 'https://crown-cars.com/photos/shares/3.jpg'}
      ]
    }
  },
  methods: {
    getContent (slug) {
      axios.get('/api/page/'+slug)
      .then(
        (response) => {
          this.pageVal = response.data;
          this.$parent.titleService = this.pageVal.title;
          this.$parent.priceService = this.pageVal.j_data.price.value;
        }
      )
      .catch(
        (error) => {
          console.log(error);
          this.$router.push('404');
        }
      );
    }
  },
  created: function() {
    this.getContent(this.$route.params.slug);
  },

  beforeRouteUpdate (to, from, next) {
    this.getContent(to.params.slug);
    next();
  }
}
</script>