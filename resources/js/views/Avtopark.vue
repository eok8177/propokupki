<template>
    <main class="main">
        <div class="wrapper main__wrapper">
            <h2 class="main__title">Автопарк</h2>
            <p class="main__subtitle">Мы собрали для Вас большой автопарк транспортных средств на все случаи перевозки
                грузов. У
                нас Вы можете срочно и по низкой цене вызвать специалистов на следующих автомобилях:</p>
            <ul class="carpark">
                <li v-for="item in cars" class="carpark__item">
                    <div class="carpark__item-image">
                        <img :src="item.img" alt="">
                    </div>
                    <div class="carpark__item-info">
                        <router-link :to="'/bus/'+item.slug" class="carpark__item-title">{{item.title}}</router-link>
                        <div class="carpark__item-table">
                            <div v-for="item in item.data.j_data.main" class="carpark__item-row">
                                <span class="carpark__item-col">{{item.label}}</span>
                                <span class="carpark__item-col">{{item.value}}</span>
                            </div>
                        </div>
                        <div class="carpark__item-order">
                            <span class="carpark__item-price">{{item.data.j_data.price.value}}</span>
                            <a href="#callFrom" class="button">Заказать звонок</a>
                        </div>
                        <p class="carpark__item-text" v-html="item.data.j_data.info.value"></p>
                    </div>
                </li>
            </ul>

            <div class="text-block" v-html="page.description"></div>
        </div>

    </main>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Avtopark',
  data() {
    return {
        cars: [],
        page: {
            description: null
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
    axios.get('/api/page/avtopark')
      .then(
        (response) => {
          this.page = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
    axios.get('/api/cars')
      .then(
        (response) => {
          this.cars = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
  },
}
</script>