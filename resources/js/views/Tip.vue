<template>
  <main class="main tip">
    <div class="tip__wrapper wrapper">
      <div class="tip__content">
        <div class="tip__content-head">
          <a href="#" @click="goBack" class="tip__content-btn">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
              d="M16 7H3.8L9.4 1.4L8 0L0 8L8 16L9.4 14.6L3.8 9H16V7Z" fill="black"/>
            </svg>
          </a>
          <h1 class="tip__title">{{tip.title}}</h1>
        </div>
        <div class="tip__img">
          <img :src="tip.image" :alt="tip.title">
        </div>
        <div class="tip__text" v-html="tip.description"></div>

      </div>
      <aside class="tip__sidebar">
        <div class="tip__sidebar-block">
          <h3 class="tip__sidebar-title">Советы</h3>
          <router-link to="/tips" class="tip__sidebar-link">Все советы</router-link>
          <template v-for="item in tips">
            <router-link :to="'/tip/'+item.slug" class="tip__sidebar-link">{{item.title}}</router-link>
          </template>

        </div>
        <div class="tip__sidebar-block">
          <h3 class="tip__sidebar-title">Архив</h3>
          <a href="#" class="tip__sidebar-link">2018</a>
          <a href="#" class="tip__sidebar-link">2017</a>
        </div>
      </aside>
    </div>
    <div class="tip__bottom wrapper">
      <h3 class="tip__bottom-title">Вам будет интересно</h3>
      <div class="tip__bottom-articles">
        <div v-for="(item, index) in tips.slice(0, 2)" class="tip__bottom-item" :class="'tip__bottom--'+(index+1)">
          <h3 class="bottom-item__title">{{item.title}}</h3>
          <p class="bottom-item__text" v-html="item.text"></p>
          <router-link :to="'/tip/'+item.slug" class="button">Читать</router-link>
        </div>
      </div>

    </div>
  </main>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'Tip',
    data() {
      return {
        tip: [],
        tips: []
      }
    },
    metaInfo() {
      return {
        title: this.tip.meta_title,
        meta: [
          { vmid: 'keywords', name: 'keywords', content: this.tip.meta_keywords},
          { vmid: 'description', name: 'description', content: this.tip.meta_description},
          { vmid: 'og:title', property: 'og:title', content: this.tip.og_title},
          { vmid: 'og:description', property: 'og:description', content: this.tip.og_description},
        { vmid: 'og:image', property: 'og:image', content: 'https://crown-cars.com/photos/shares/3.jpg'}
        ]
      }
    },
    methods: {
      goBack () {
        window.history.length > 1
        ? this.$router.go(-1)
        : this.$router.push('/tips')
      },
      getContent (slug) {
        axios.get('/api/tip/'+slug)
        .then(
          (response) => {
            this.tip = response.data;
          }
        )
        .catch(
          (error) => console.log(error)
        );
      }
    },
    created: function() {
      this.getContent(this.$route.params.slug);
      axios.get('/api/tips')
          .then(
              (response) => {
                  this.tips = response.data;
              }
          )
          .catch(
              (error) => console.log(error)
          );
    },

    beforeRouteUpdate (to, from, next) {
      this.getContent(to.params.slug);
      next();
    }
  }
</script>