@extends('cutup.admin.layout')

@section('content')

<div class="container">
  <h1 class="title-page">Новая акция</h1>
</div>
<hr>

<div class="actions" id="actionsForm">
<form action="">
  <div class="container">

    <div class="form-group pl-0 col-md-4">
      <label>Магазин</label>
      <p>Выберите магазин в котором будет акция</p>

      <div class="search">
        <input type="text" placeholder="Введите название">
        <button class="btn-search"></button>
      </div>
    </div>

    <div class="filtered">
      <div class="item">
        <button class="btn-delete"></button>
        <div class="image"><img src="/images/shop-1.jpg" alt=""></div>
      </div>
      <div class="item">
        <button class="btn-delete"></button>
        <div class="image"><img src="/images/shop-1.jpg" alt=""></div>
      </div>
      <div class="item">
        <button class="btn-delete"></button>
        <div class="image"><img src="/images/shop-1.jpg" alt=""></div>
      </div>
    </div>

    <div class="form-group col-md-4 pl-0">
      <label>Название</label>
      <p>Укажите название акции</p>
      <input type="text" class="form-control" placeholder="Название">
    </div>

    <div class="form-group pl-0 select-dates">
      <label>Период действия</label>
      <p>Укажите даты начала и окончания действия акции</p>
      <div class="d-md-flex">
        <div class="date"><input type="text" class="form-control"></div>
        <div class="date"><input type="text" class="form-control"></div>
      </div>
    </div>

  </div>
  <hr>

{{-- Добавленые продукты, показывать при редактировании --}}
  <div class="container">
    <div class="products" id="products">

      <div class="item row" v-for="product in products">
        <span class="gray-title">Товар @{{product.id}}</span>
        <button class="btn-delete" v-on:click.stop.prevent="onDeleteProduct(product.id)"></button>
        <div class="col-md-4 col-xl-6">
          <div class="form-group">
            <label>Фото</label>
            <p>Загрузите фото товара</p>
            <div class="files-input">
              <img :src="product.image"/>
              <input type="file" class="form-control input-img">
              <span class="btn-red">Добавить фото</span>
              <span class="text">Или перетащите его сюда</span>
            </div>
          </div>
        </div>
        <div class="col-md-8 col-xl-6">
          <div class="form-group col-xl-8 pl-0">
            <label>Название</label>
            <p>Укажите название товара</p>
            <input type="text" class="form-control" placeholder="Название" v-model="product.title">
          </div>

          <div class="form-group col-xl-8 pl-0 units-select">
            <label>Количество и вес</label>
            <p>Укажите количество или вес продукта (шт, л, кг)</p>
            <div class="fields">
              <input type="text" class="form-control" placeholder="Количество или вес" v-model="product.count">
              <div class="select">
                <select class="custom-select" name="" id="">
                  <option selected="selected">шт</option>
                  <option >кг</option>
                  <option >л</option>
                  <option >уп</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group pl-0 price-input">
            <label>Цена</label>
            <p>Укажите старую цену и размер скидки, новая цена посчитается автоматически</p>
            <div class="fields">
              <input type="text" class="form-control price" placeholder="Старая цена грн" v-model="product.price">
              <input type="text" class="form-control discount" placeholder="Размер скидки %" v-model="product.discount">
              <span class="new-price">Новая цена: <span class="result">@{{(product.price - product.price * product.discount / 100).toFixed(2)}}</span></span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <hr>

{{-- Форма добавления продукта --}}
  <div class="container" id="add_new_product">

    <div class="form-group">
      <label>Фото</label>
      <p>Загрузите фото товара</p>
      <div class="files-input">
        <img src=""/>
        <input type="file" id="file" ref="file" class="form-control input-img" v-on:change="handleFileUpload()">
        <span class="btn-red">Добавить фото</span>
        <span class="text">Или перетащите его сюда</span>
      </div>
    </div>

    <div class="form-group col-md-4 pl-0">
      <label>Название</label>
      <p>Укажите название товара</p>
      <input type="text" class="form-control" placeholder="Название" v-model="title">
    </div>

    <div class="form-group col-md-4 pl-0 units-select">
      <label>Количество и вес</label>
      <p>Укажите количество или вес продукта (шт, л, кг)</p>
      <div class="fields">
        <input type="text" class="form-control" placeholder="Количество или вес" v-model="count">
        <div class="select">
          <select class="custom-select" name="" id="">
            <option selected="selected">шт</option>
            <option >кг</option>
            <option >л</option>
            <option >уп</option>
          </select>
        </div>
      </div>
    </div>

    <div class="form-group pl-0 price-input">
      <label>Цена</label>
      <p>Укажите старую цену и размер скидки, новая цена посчитается автоматически</p>
      <div class="fields">
        <input type="text" class="form-control price" placeholder="Старая цена грн" v-model="price">
        <input type="text" class="form-control discount" placeholder="Размер скидки %" v-model="discount">
        <span class="new-price">Новая цена: <span class="result"></span></span>
      </div>
    </div>

  </div>

  <div class="container">
    <button class="btn btn-red" v-on:click.stop.prevent="onAddProduct">Добавить товар</button>
  </div>

{{-- Кнопки сохранения --}}
  <div class="container">
    <div class="form-group">
      <div class="submit-btns">
        <button class="btn btn-red" type="submit">Обновить акцию</button>
        <button class="btn btn-white">Отмена</button>
      </div>
    </div>
  </div>


</form>
</div>


@endsection

@push('styles')
<link href="{{ asset('vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/datepicker/locales/bootstrap-datepicker.ru.min.js') }}"></script>
<!-- VueJS -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
  $(function() {
    $('.date input').datepicker({
      language: "ru"
    });
    //calculate discount
    $('body').on('change', '.price, .discount', function(){
      var fields = $(this).parent();
      var price = fields.find('.price').val();
      var discount = fields.find('.discount').val();
      fields.find('.result').text(price - price*discount/100);
    });

    $('body').on('change', '.input-img', function(e) {
      handleImage(e);
    });

  });
/* Vue from here )) */
  var app = new Vue({
    el: '#actionsForm',
    data() {
        return {
          products: [],
          actionID: 1, //set from laravel
          img: '',
          title: '',
          count: '',
          price: '',
          discount: ''
        }
    },
    created: function() {
      axios.get('/api/admin-actions/'+this.actionID)
        .then(
          (response) => {
            this.products = response.data;
          }
        )
        .catch(
          (error) => console.log(error)
        );
    },
    methods: {
      handleFileUpload(){
        this.img = this.$refs.file.files[0];
      },
      onAddProduct(event) {
        const formData = new FormData();
        formData.append('image', this.img);
        formData.append('title', this.title);
        formData.append('count', this.count);
        formData.append('price', this.price);
        formData.append('discount', this.discount);
        const config = {
          headers: {
            'content-type': 'multipart/form-data'
          }
        };
        axios.post('/api/admin-actions/'+this.actionID, formData, config)
          .then(
            (response) => {
              this.products = response.data;
            }
          )
          .catch(
            (error) => console.log(error)
          );
      },
      onDeleteProduct(product) {
        if(confirm("Do you really want to delete?")){
          axios.delete('/api/admin-actions/'+this.actionID+'/'+product)
            .then(
              (response) => {
                this.products = response.data;
              }
            )
            .catch(
              (error) => console.log(error)
            );
          }
      },

    }
  })
</script>
@endpush