@extends('cutup.admin.layout')

@section('content')

<div class="container">
  <h1 class="title-page">Новая акция</h1>
</div>
<hr>

<div class="actions">
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
      Добавленые продукты
    </div>
  </div>
  <hr>

{{-- Форма добавления продукта --}}
  <div class="container" id="add_new_product">

    <div class="form-group">
      <label>Фото</label>
      <p>Загрузите фото товара</p>
      <div class="files-input">
        <input type="file" class="form-control" multiple="">
        <span class="btn-red">Добавить фото</span>
        <span class="text">Или перетащите его сюда</span>
      </div>
    </div>

    <div class="form-group col-md-4 pl-0">
      <label>Название</label>
      <p>Укажите название товара</p>
      <input type="text" class="form-control" placeholder="Название">
    </div>

    <div class="form-group col-md-4 pl-0 units-select">
      <label>Количество и вес</label>
      <p>Укажите количество или вес продукта (шт, л, кг)</p>
      <div class="fields">
        <input type="text" class="form-control" placeholder="Количество или вес">
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
        <input type="text" class="form-control price" placeholder="Старая цена грн">
        <input type="text" class="form-control discount" placeholder="Размер скидки %">
        <span class="new-price">Новая цена: <span class="result"></span></span>
      </div>
    </div>

  </div>

{{-- Кнопки сохранения --}}
  <div class="container">
    <div class="form-group">
      <div class="submit-btns">
        <button class="btn btn-red" type="submit">Добавить акцию</button>
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
  });
</script>
@endpush