<template>
    <main class="main reviews-page">
        <div class="wrapper main__wrapper">
            <h2 class="main__title">Отзывы</h2>
            <p class="main__subtitle reviews__subtitle">Мы собрали для Вас большой автопарк транспортных средств на все случаи перевозки
                грузов. У нас Вы можете срочно и по низкой цене вызвать специалистов на следующих автомобилях:</p>
            <div class="button-center reviews__btn">
                <a href="#" class="button " id="review-btn">Добавить отзыв</a>
            </div>
            <ul class="reviews-big">

              <paginate
                name="paginate"
                :list="reviews"
                :per="5"
              >
                <li v-for="review in paginated('paginate')" class="reviews-big__item">
                    <div class="reviews-big__img">
                        <img :src="review.image" alt="">
                    </div>
                    <div class="reviews-big__content">
                        <h3 class="reviews-big__title">{{review.title}}</h3>
                        <span class="reviews-big__data">{{review.date}}</span>
                        <p class="reviews-big__text" v-html="review.text"></p>
                        <div class="reviews__star">
                            <img src="img/star.svg" alt="" :data-rate="review.rating">
                        </div>
                    </div>
                </li>
              </paginate>
            </ul>

            <paginate-links
              for="paginate"
              :classes="{
                'ul': 'breadcrumbs',
                'li': 'breadcrumbs__item',
                'a': 'breadcrumbs__link'
              }"
            ></paginate-links>
        </div>


        <div class="review__form" style="position: fixed;">
            <button class="review__form-close">×</button>
            <form @submit="sendForm" id="reviewForm">
                <h3 class="review__form-title">Оставте свой отзыв</h3>
                <div class="review__form-block">
                    <label for="yourname" class="review__form-label">Как Вас зовут?</label>
                    <input v-model="rev.title" type="text" name="yourname" id="yourname" class="review__form-field" placeholder="Ваше Имя" required="required">
                </div>
                <div class="review__form-block">
                    <label for="type" class="review__form-label">Вид перевозки</label>
                    <select v-model="rev.type" name="type" id="type" class="review__form-field">
                        <option v-for="item in services">{{ item.title }}</option>
                    </select>
                </div>
                <div class="review__form-block">
                    <label for="yourmessage" class="review__form-label">Сообщение</label>
                    <textarea v-model="rev.text" name="yourname" id="yourmessage" class="review__form-field review__form-message"
                              placeholder="Расскажите нам историю..." required="required">
                    </textarea>
                </div>
                <div class="review__form-star">
                    <div id="reviewStars-input">
                        <input id="star-4" type="radio" name="reviewStars" value="5" v-model="rev.rating"/>
                        <label title="gorgeous" for="star-4"></label>

                        <input id="star-3" type="radio" name="reviewStars" value="4" v-model="rev.rating"/>
                        <label title="good" for="star-3"></label>

                        <input id="star-2" type="radio" name="reviewStars" value="3" v-model="rev.rating"/>
                        <label title="regular" for="star-2"></label>

                        <input id="star-1" type="radio" name="reviewStars" value="2" v-model="rev.rating"/>
                        <label title="poor" for="star-1"></label>

                        <input id="star-0" type="radio" name="reviewStars" value="1" v-model="rev.rating"/>
                        <label title="bad" for="star-0"></label>
                    </div>
                </div>
                <div v-show="status" style="text-align: center;padding-bottom: 14px;"><p>{{message}}</p></div>
                <div class="button-center">
                    <button type="submit" class="button">Оставить отзыв</button>
                </div>

            </form>
        </div>
        <div class="overlay"></div>

    </main>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Reviews',

  data() {
    return {
        reviews: [],
        paginate: ['paginate'],
        rev: {
          title: null,
          type: null,
          reting: null,
          text: null
        },
        status: false,
        message: null,
        services: []
    }
  },
  created: function() {
    axios.get('/api/reviews')
      .then(
          (response) => {
              this.reviews = response.data;
          }
      )
      .catch(
          (error) => console.log(error)
      );
    axios.get('/api/services')
      .then(
        (response) => {
          this.services = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
  },
  methods: {
    sendForm: function(e) {
      e.preventDefault();
      axios.post('/api/review', this.rev)
      .then(
        (response) => {
          this.message = 'Спасибо, за Ваш отзыв';
          this.status = true;
          this.rev.title = null;
          this.rev.reting = null;
          this.rev.text = null;
          console.log(response);
        }
      )
      .catch(
        (error) => console.log(error)
      );
    }
  },
  metaInfo() {
    return {
      title: 'Отзывы',
      meta: [
        { vmid: 'keywords', name: 'keywords', content: 'Отзывы'},
        { vmid: 'description', name: 'description', content: 'Отзывы'},
        { vmid: 'og:title', property: 'og:title', content: 'Отзывы'},
        { vmid: 'og:description', property: 'og:description', content: 'Отзывы'},
        { vmid: 'og:image', property: 'og:image', content: 'https://crown-cars.com/photos/shares/3.jpg'}
      ]
    }
  },
}
</script>


