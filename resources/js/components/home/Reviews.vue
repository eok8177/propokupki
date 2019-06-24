<template>
  <div class="reviews">
    <div class="wrapper">
      <h3 class="section-title">Отзывы клиентов,<br> переехавших с нами</h3>
      <p class="section-subtitle">Тысячи довольных клиентов, десятки тонн перевезенного груза, десятки литров выпитого
      менеджерами кофе, сотни выкуренных грузчиками сигарет.</p>
      <ul class="reviews__items">
        <li v-for="review in reviews" class="reviews__item">
          <div class="reviews__img">
            <img :src="review.img" alt="">
          </div>
          <p class="reviews__name">{{review.name}}</p>
          <p class="reviews__text">{{review.text}}</p>
        </li>
      </ul>
      <div class="button-center">
        <router-link to="/reviews" class="button button-border">Все отзывы</router-link>
      </div>
    </div>
  </div>
</template>

<script>

  export default {
    name: 'Reviews',
    props: ['homeReviews'],
    data() {
      return {
        reviews: this.homeReviews
      }
    }
  }
</script>