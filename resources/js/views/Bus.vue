<template>
  <main class="main bus">
    <div class="wrapper main__wrapper">
      <h2 class="main__title">{{page.h1}}</h2>
      <p class="main__subtitle">Мы собрали для Вас большой автопарк транспортных средств на все случаи перевозки
      грузов. У нас Вы можете срочно и по низкой цене вызвать специалистов на следующих автомобилях:</p>

      <div class="sticker">
        <div class="sticker__wrapper">
          <div class="sticker__img">
            <img :src="page.img" :alt="page.title">
          </div>
          <div class="sticker__info">
            <div class="sticker__info-tech">
              <h3 class="sticker__title">Характеристики автомобиля</h3>

              <div class="carpark__item-table">
                  <div v-for="item in page.j_data.main" class="carpark__item-row">
                      <span class="carpark__item-col">{{item.label}}</span>
                      <span class="carpark__item-col">{{item.value}}</span>
                  </div>
              </div>

            </div>
            <div class="sticker__info-more">
              <h3 class="sticker__title">Дополнительная информация</h3>
              <div class="carpark__item-table">

                <div v-for="item in page.j_data.addinfo" class="carpark__item-row">
                  <span class="carpark__item-col">{{item.label}}</span>
                  <span class="carpark__item-col">{{item.value}}</span>
                </div>

              </div>
            </div>
            <div class="sticker__order">
              <div class="carpark__item-order">
                <span class="carpark__item-price">{{page.j_data.price.value}}</span>
                <a href="#" class="button">Заказать звонок</a>
              </div>
              <p class="carpark__item-text" v-html="page.j_data.info.value"></p>
            </div>
          </div>
        </div>

      </div>
      <div class="text-block" v-html="page.description"></div>
      <div class="button-center">
        <router-link to="/avtopark" class="button button-border">Весь автопарк</router-link>
      </div>
    </div>

  </main>
</template>

<script>
  import axios from 'axios';
  export default {
    name: 'Bus',
    data() {
      return {
        page: {
          j_data: {
            main: [],
            addinfo: [],
            info: [],
            price: []
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
      axios.get('/api/page/'+this.$route.params.slug)
      .then(
        (response) => {
          this.page = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
    },
  }
</script>