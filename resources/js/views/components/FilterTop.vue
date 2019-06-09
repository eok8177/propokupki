<template>
  <div class="top-filter container">
    <div class="filter">
      <div class="first-line">

        <div class="select">
          <button class="dropdown btn-select" @click="toggle('sort')" v-bind:class="{'open' : dropDowns.sort}">
            {{select.sort[filter.sort]}}
          </button>
          <ul>
            <li 
              v-for="(value,name) in select.sort"
              @click="setSort(name, 'sort')">
              {{value}}
            </li>
          </ul>
        </div>

        <button class="btn" @click="toggle('shops')">Магазины <span>238</span></button>
        <button class="btn">Все категории</button>

        <div class="select">
          <button class="dropdown btn-select" @click="toggle('dates')" v-bind:class="{'open' : dropDowns.dates}">
            {{select.dates[filter.dates]}}
          </button>
          <ul>
            <li 
              v-for="(value,name) in select.dates"
              @click="setSort(name, 'dates')">
              {{value}}
            </li>
          </ul>
        </div>

      </div>
      <div class="second-line">
        <div class="shops" v-bind:class="{'open' : dropDowns.shops}">
          <div class="inner">
            <div class="search">
              <input type="text" placeholder="Введите название" v-model.trim="search_shops">
              <button class="close">X</button>
            </div>
            <ul>
              <li v-for="shop in shops">{{shop.title}}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>


    <div class="result"></div>
  </div>

</template>

<script>

export default {
  name: 'FilterTop',
  data() {
    return {
      shops: [],
      search_shops: '',
      categories: [],
      select: {
        sort: {
          'new': 'Новые',
          'asc': 'От дешевых к дорогим',
          'desc': 'От дорогих к дешевым'
        },
        dates: {
          'all': 'Текушие и будущие',
          'now': 'Текушие',
          'feature': 'Будущие',
          'past': 'Прошедшие',
        }
      },
      dropDowns: {
        sort: false,
        shops: false,
        categories: false,
        dates: false
      },
      filter: {
        sort: 'new',
        shops: '',
        categories: '',
        dates: 'all',
      },
    }
  },
  watch: {
    search_shops: function (newQuestion, oldQuestion) {
      this.debouncedSearchShops()
    }
  },
  created: function () {
    this.debouncedSearchShops = _.debounce(this.searchShops, 500);
    axios.get('/api/shops')
      .then(
        (response) => {
          this.shops = response.data;
        }
      )
      .catch(
        (error) => console.log(error)
      );
  },
  methods: {
    toggle: function(name) {
       this.dropDowns[name] = !this.dropDowns[name];
    },
    setSort: function(key, name) {
      this.filter[name] = key;
      this.dropDowns[name] = !this.dropDowns[name];
      this.$parent.filtered(this.filter);
    },
    searchShops: function () {
      if (this.search_shops.length < 3) return;
      axios.post('/api/shops', {data: this.search_shops})
        .then(
          (response) => {
            this.shops = response.data;
          }
        )
        .catch(
          (error) => console.log(error)
        );
    }
  },

}
</script>

<style scoped lang=scss>
  .dropdown {
    + ul {display: none;}
    &.open + ul {display: block;}
  }
</style>