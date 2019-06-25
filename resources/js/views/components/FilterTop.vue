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

        <button class="btn" @click="toggle('shops')">Магазины <span>{{shops.length}}</span></button>
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
              <button class="ico ico-delete" @click="allShops"></button>
            </div>
            <ul>
              <li v-for="shop in shops" class="checkbox">
                <input type="checkbox" :id="'shop_'+shop.id" v-model="shopsSelected" :value="shop.id">
                <label :for="'shop_'+shop.id">{{shop.title}}</label>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>


    <div class="result">
      <span class="shop-selected" v-for="item in shopsSelected">
        <img :src="shops[item].image" :alt="shops[item].title">
        <span class="btn-delete" @click="removeShop(item)"></span>
      </span>

      <button class="btn btn-red" v-if="shopsSelected.length" @click="resetFilter">Очистить</button>
    </div>
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
      shopsSelected: []
    }
  },
  watch: {
    search_shops: function () {
      this.debouncedSearchShops()
    },
    shopsSelected: function (value) {
      this.filter.shops = value.toString();
      this.$parent.filtered(this.filter);
    }
  },
  created: function () {
    this.debouncedSearchShops = _.debounce(this.searchShops, 500);
    this.allShops();
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
      axios.post('/api/shops-search', {
          search: this.search_shops,
          city: localStorage.cityId
        })
        .then(
          (response) => {
            this.shops = response.data;
          }
        )
        .catch(
          (error) => console.log(error)
        );
    },
    allShops: function() {
      axios.get('/api/shops/?city='+localStorage.cityId)
        .then(
          (response) => {
            this.shops = response.data;
            this.search_shops = '';
            this.filter.shops = '';
            this.$parent.filtered(this.filter);
          }
        )
        .catch(
          (error) => console.log(error)
        );
    },
    removeShop: function(item) {
      var index = this.shopsSelected.indexOf(item);
      if (index > -1) this.shopsSelected.splice(index, 1);
    },
    resetFilter: function() {
      this.filter = {
        sort: 'new',
        shops: '',
        categories: '',
        dates: 'all',
      };
      this.dropDowns = {
        sort: false,
        shops: false,
        categories: false,
        dates: false
      };
      this.shopsSelected = [];
      this.$parent.filtered(this.filter);
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