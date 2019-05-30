<template>
  <div id="app">

    <page-header></page-header>

    <router-view></router-view>

    <page-footer></page-footer>

  </div>
</template>

<script>
import axios from 'axios';
import PageHeader from '@/views/components/PageHeader';
import PageFooter from '@/views/components/PageFooter';

export default {
  name: 'App',
  components: {
    PageHeader,
    PageFooter
  },
  data() {
    return {
    }
  },
  created: function() {
  },
}
</script>
