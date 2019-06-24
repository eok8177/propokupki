<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'App',
  components: {
  },
  data() {
    return {
      home: false,
      headerClass: false,
      menuPages: [],
      titleService: false,
      priceService: false,
    }
  },
  created: function() {
    axios.get('/api/services')
      .then(
        (response) => {
          this.menuPages = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
  },
}
</script>
