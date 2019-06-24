<template>
    <main class="main">
        <div class="wrapper main__wrapper">
            <h2 class="main__title">Coветы</h2>
            <p class="main__subtitle">Мы собрали для Вас большой автопарк транспортных средств на все случаи перевозки
                грузов. У нас Вы можете срочно и по низкой цене вызвать специалистов на следующих автомобилях:</p>

            <ul class="tips">
                <li v-for="tip in tips" class="tips__item">
                    <div class="tips__item-img">
                        <img :src="tip.image" alt="">
                    </div>
                    <div class="tips__content">
                        <h3 class="tips__item-title">{{tip.title}}</h3>
                        <p class="tips__item-text" v-html="tip.text"></p>
                        <router-link :to="'/tip/'+tip.slug" class="button">Читать</router-link>
                    </div>

                </li>
            </ul>

        </div>

    </main>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Tips',
  data() {
    return {
        tips: []
    }
  },
  created: function() {
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
  metaInfo() {
    return {
      title: 'Советы',
      meta: [
        { vmid: 'keywords', name: 'keywords', content: 'Советы'},
        { vmid: 'description', name: 'description', content: 'Советы'},
        { vmid: 'og:title', property: 'og:title', content: 'Советы'},
        { vmid: 'og:description', property: 'og:description', content: 'Советы'},
        { vmid: 'og:image', property: 'og:image', content: 'https://crown-cars.com/photos/shares/3.jpg'}
      ]
    }
  },
}
</script>