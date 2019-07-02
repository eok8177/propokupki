<template>
  <div class="top-filter container">
    <div class="filter">
      <div class="first-line">

        <div class="select">
          <button class="dropdown btn-select" @click="toggle('sort')" v-bind:class="{'open active' : dropDowns.sort}">
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

        <button class="btn" @click="toggle('shops')" v-bind:class="{ active: dropDowns.shops }">Магазини <span>{{shopCount}}</span></button>
        <!-- <button class="btn">Всі категорії</button> -->

        <div class="select">
          <button class="dropdown btn-select" @click="toggle('dates')" v-bind:class="{'open active' : dropDowns.dates}">
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
              <input type="text" placeholder="Введіть назву" v-model.trim="search_shops">
              <button class="ico ico-delete" @click="allShops(true)"></button>
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
      <span class="shop-selected" v-if="filter.sort == 'asc' || filter.sort == 'desc'">
        {{select.sort[filter.sort]}}
        <span class="btn-delete" @click="removeSort('sort')"></span>
      </span>

      <span class="shop-selected" v-for="item in shopsSelected">
        <img :src="shopsAll[item].image" :alt="shopsAll[item].title">
        <span class="btn-delete" @click="removeShop(item)"></span>
      </span>

      <span class="shop-selected" v-if="filter.dates == 'now' || filter.dates == 'feature' || filter.dates == 'past'">
        {{select.dates[filter.dates]}}
        <span class="btn-delete" @click="removeSort('dates')"></span>
      </span>

      <button class="btn btn-red" 
        v-if="shopsSelected.length || filter.sort == 'asc' || filter.sort == 'desc' || filter.dates == 'now' || filter.dates == 'feature' || filter.dates == 'past'" 
        @click="resetFilter"
      >Очистити</button>
    </div>
  </div>

</template>

<script>

export default {
  name: 'FilterTop',
  props: ['shop'],
  data() {
    return {
      shops: [],
      shopsAll: [],
      shopCount: '',
      search_shops: '',
      categories: [],
      select: {
        sort: {
          'new': 'Нові',
          'asc': 'Від дешевих до дорогих',
          'desc': 'Від дорогих до дешевих'
        },
        dates: {
          'all': 'Поточні та майбутні',
          'now': 'Поточні',
          'feature': 'Майбутні',
          'past': 'Минулі',
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
            this.shopCount = Object.keys(this.shops).length;
          }
        )
        .catch(
          (error) => console.log(error)
        );
    },
    allShops: function(reset) {
      axios.get('/api/shops/?city='+localStorage.cityId)
        .then(
          (response) => {
            this.shopsAll = response.data;
            this.shops = response.data;
            this.shopCount = Object.keys(this.shopsAll).length;
            this.search_shops = '';
            this.filter.shops = this.shop;
            if (this.shop && !reset) {
              this.shopsSelected = [this.shop];
            } else {
              this.shopsSelected = [];
              this.$parent.filtered(this.filter);
            }
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
    removeSort: function(filter) {
      if (filter == 'sort') {
        this.filter.sort = 'new';
      }
      if (filter == 'dates') {
        this.filter.dates = 'all';
      }
      this.$parent.filtered(this.filter);
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