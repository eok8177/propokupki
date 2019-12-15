<template>
  <div class="home-page">

    <div class="container page-title">
      <h1 class="title">Акції та знижки {{cityName2}}</h1>
      <p class="sub-title">{{shopCount}}+ магазинів з кращими пропозиціями</p>
    </div>

    <div class="slider" :class="{'show-all-shops' : showAllShops}">
      <div class="container">
        <div class="wrap">

          <div class="shop" v-for="shop in shops">
            <router-link :to="{ name: 'Actions', query: {shop: shop.id} }">
              <div class="image">
                <img :src="shop.image" :alt="shop.title">
              </div>
              <p><span class="count">{{shop.shops}}</span> {{getNumEnding(shop.shops, ['Магазин','Магазини','Магазинів'])}} </p>
              <p><span class="count">{{shop.products}}</span> {{getNumEnding(shop.products, ['Акція','Акції','Акцій'])}} </p>
              <p class="discount" v-if="shop.discount > 0">Знижки до <span class="number">-{{shop.discount}}%</span></p>
            </router-link>
          </div>

          <div class="shop" v-if="!showAllShops">
            <div class="ico ico-bag"></div>
            <p class="title">Більше {{shopCount}}</p>
            <p class="gray">Магазинів</p>
            <hr>

            <!-- <div class="dropdown"> -->
              <button class="btn btn-red" @click="viewAllShops()">
                Усі магазини
              </button>
            <!-- </div> -->
          </div>

        </div>
      </div>
    </div>

    <h2 class="block-title">Кращі акції {{cityName2}}</h2>
    <products :products="actions" homePage="true"></products>

    <div class="container">
      <div class="page-title">
        <h1 class="title">Акції магазинів торгових мереж</h1>
      </div>

      <div class="text-page">
        <p>Якщо хочете купувати потрібні Вам продукти і при цьому економити, але не бажаєте бігати по магазинах у пошуках найвигіднішої пропозиції, скористайтеся нашим сервісом. Ви першими дізнаєтесь про діючі акції магазинів торгових мереж на продукти харчування, одяг і взуття, предмети побутової хімії, косметику і багато іншого. Ваш похід в магазин стане приємним і вигідним!</p>

        <h2>Дивитися акції та помічати знижки першими – отримайте Вашу реальну вигоду!</h2>

        <p>На нашому сайті Ви зможете знайти інформацію , де є знижка в великих магазинах Вашого міста і країни, причому не тільки у продуктових супермаркетах. Ми відстежуємо акційні пропозиції та знижки на продукти і затребувані товари повсякденного попиту таких великих мереж як:</p>

        <ul>
          <li>АТБ;</li>
          <li>Сільпо; </li>
          <li>ЕКО маркет;</li>
          <li>Billa; </li>
          <li>Брусничка; </li>
          <li>Космо; </li>
          <li>Рукавичка; </li>
          <li>Фуршет;</li>
          <li>Metro.</li>
        </ul>

        <p>Це не повний список магазинів, інформація про акційні пропозиції яких представлена ​​на нашому веб-ресурсі. Від Вас не сховається жодна знижка - Ви можете переглядати як поточні пропозиції, так і майбутні, і навіть минулі. Щоб дізнатися про вигідні пропозиції на конкретний товар, який пропонує та чи інша мережа супермаркетів, скористайтеся рядком пошуку або перегляньте відповідну категорію. Сервіс дозволяє не тільки знаходити потрібний товар за мінімальною ціною, а й порівнювати його вартість з ціною аналогічних продуктів. Створіть свій каталог знижок і улюблених товарів!</p>

        <p><i>Живіть яскраво, витрачайте мало</i></p>

        <p>Наш сайт створений для тих, хто вміє економити! Життя людей, які стали нашими постійними відвідувачами, кардинально змінюється, тому що вони більше не роблять емоційних дорогих покупок. Продуманий шопінг - це придбання тільки потрібних товарів за найвигіднішою ціною! Залишайтеся з нами, і Ви першими дізнаєтесь про чудові акції та пропозиції, будете в курсі всіх розпродажів. Ваш будинок ставатиме затишнішим, а гаманець - товстішим!</p>
        <p>&nbsp;</p>
      </div>
    </div>


  </div>
</template>

<script>
import axios from 'axios';
import Products from '@/views/components/Products';

export default {
  name: 'Home',
  components: {
    Products
  },
  data() {
    return {
        shops: [],
        actions: [],
        cityName2: '',
        shopCount: '230',
        dropDowns: {
          shops: false,
        },
        showAllShops: false,
        meta: {
          title: 'ProPokupki - Акції та знижки України',
          description: 'Акції та знижки всіх популярних магазинів України в одному місці',
        }
    }
  },
  created: function() {
    this.getAll();
  },
  mounted() {
    this.$root.$on('cityChanged', () => {
      this.getAll();
    })
  },
  methods: {
    getAll: function() {
      axios.get('/api/shops/?city='+localStorage.cityId+'&count=5')
        .then(
          (response) => {
            this.shops = response.data;
            this.cityName2 = localStorage.cityName2;
          }
        )
        .catch(
          (error) => console.log(error)
        );
        axios.get('/api/actions/?city='+localStorage.cityId)
          .then(
            (response) => {
              this.actions = response.data;
            }
          )
          .catch(
            (error) => console.log(error)
          );
    },

    selectShop: function(slug) {
      this.$router.push({ name: 'Actions', params: { shop: slug }});
      // window.location.href = "/actions"+slug;
    },

    toggle: function(name) {
       this.dropDowns[name] = !this.dropDowns[name];
    },

    viewAllShops: function() {
      axios.get('/api/shops/?city='+localStorage.cityId)
        .then(
          (response) => {
            this.shops = response.data;
            this.cityName2 = localStorage.cityName2;
            this.showAllShops = true;
          }
        )
        .catch(
          (error) => console.log(error)
        );
    },

    /**

     * Функция возвращает окончание для множественного числа слова на основании числа и массива окончаний
     * @param  iNumber Integer Число на основе которого нужно сформировать окончание
     * @param  aEndings Array Массив слов или окончаний для чисел (1, 4, 5),
     *         например ['яблоко', 'яблока', 'яблок']
     * @return String
     */
    getNumEnding: function(iNumber, aEndings) {
        var sEnding, i;
        iNumber = iNumber % 100;
        if (iNumber>=11 && iNumber<=19) {
            sEnding=aEndings[2];
        }
        else {
            i = iNumber % 10;
            switch (i)
            {
                case (1): sEnding = aEndings[0]; break;
                case (2):
                case (3):
                case (4): sEnding = aEndings[1]; break;
                default: sEnding = aEndings[2];
            }
        }
        return sEnding;
    }

  },

  metaInfo() {
    return {
      title: this.meta.title,
      meta: [
        { vmid: 'description', name: 'description', content: this.meta.description},
        { vmid: 'og:title', property: 'og:title', content: this.meta.title},
        { vmid: 'og:description', property: 'og:description', content: this.meta.description},
      ]
    }
  },
}
</script>