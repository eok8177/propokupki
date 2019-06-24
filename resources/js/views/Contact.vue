<template>
  <div class="product-page">

    <div class="container page-title">
      <h1 class="title">Связаться с нами</h1>
    </div>

    <div class="form">
      <div class="container">

        <form @submit.prevent="submitForm" >

          <div class="form-field">
            <input
              id="name"
              v-model="name"
              type="text"
              name="name"
              placeholder="Имя"
            >
            <p class="error" v-if="errors.name">{{errors.name}}</p>
          </div>


          <div class="form-field">
            <input
              id="email"
              v-model="email"
              type="email"
              name="email"
              placeholder="E-mail"
            >
            <p class="error" v-if="errors.email">{{errors.email}}</p>
          </div>

          <div class="form-field">
            <textarea v-model="message" name="message" placeholder="Сообщение…"></textarea>
            <p class="error" v-if="errors.message">{{errors.message}}</p>
          </div>

          <div class="form-field">
            <button type="submit">Отправить</button>
          </div>

        </form>


      </div>
    </div>


  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Contact',
  components: {
  },
  data() {
    return {
      errors: {
        hasError: false,
        name: null,
        email: null,
        message: null,
      },
      name: null,
      email: null,
      message: null,
    }
  },
  methods: {
    submitForm: function (e) {

      this.errors = {
        hasError: false,
        name: null,
        email: null,
      };

      if (!this.name) {
        this.errors.name = ('Укажите имя.');
        this.errors.hasError = true;
      }
      if (!this.email) {
        this.errors.email = ('Укажите электронную почту.');
        this.errors.hasError = true;
      } else if (!this.validEmail(this.email)) {
        this.errors.email = ('Укажите корректный адрес электронной почты.');
        this.errors.hasError = true;
      }
      if (!this.message) {
        this.errors.message = ('Напишите сообщение.');
        this.errors.hasError = true;
      }

      if (!this.errors.hasError) {
        console.log('Submit Form')
      }
    },

    validEmail: function (email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    }
  }
}
</script>