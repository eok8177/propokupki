<div class="container">

    <div class="form-group pl-0 col-md-4">
        <label>Магазин</label>
        <p>Выберите магазин в котором будет акция</p>

        <div class="search">
            <input type="text" id="shop_search" data-href="{{route('admin.shops.ajaxShops')}}" placeholder="Введите название">
            <button class="btn-search"></button>
        </div>
    </div>

    <div class="filtered">
        @foreach($discount->shops as $shop)
            <div class="item">
                <button class="btn-delete" onclick="$(this).parent().remove()"></button>
                <div class="image"><img src="{{ $shop->image ? asset('/storage/'.$shop->image) : asset('/storage/no_image.jpg') }}" alt=""></div>
            </div>
        @endforeach
    </div>

    <div class="form-group col-md-4 pl-0">
        @foreach ($languages as $lang)
            {{ Form::label('title', 'Название') }}
            <p>Укажите название акции </p>
            {{ Form::text($lang->locale.'[title]', $contents[$lang->locale]->title, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control']) }}
            @if($errors->has('title'))
                <span class="invalid-feedback">{{ $errors->first('title') }}</span>
            @endif
        @endforeach
    </div>

    <div class="form-group col-md-4 pl-0">
        {{ Form::label('slug', 'Url') }}
        <p>Укажите url акции </p>
        {{ Form::text('slug', $discount->slug, ['class' => $errors->has('slug') ? 'form-control is-invalid' : 'form-control']) }}
        @if($errors->has('slug'))
            <span class="invalid-feedback">{{ $errors->first('slug') }}</span>
        @endif
    </div>

    <div class="form-group pl-0 select-dates">
        {{ Form::label('slug', 'Период действия') }}
        <p>Укажите даты начала и окончания действия акции</p>
        <div class="d-md-flex">
            <div class="date">{{ Form::text('date_start', $discount->date_start, ['class' => $errors->has('date_start') ? 'form-control is-invalid' : 'form-control']) }}</div>
            <div class="date">{{ Form::text('date_end', $discount->date_end, ['class' => $errors->has('date_end') ? 'form-control is-invalid' : 'form-control']) }}</div>
        </div>
    </div>
    {{ Form::hidden('status', 1, []) }}

</div>
<hr>
@if($method == 'edit')
{{-- Добавленые продукты, показывать при редактировании --}}
<div class="container">
    <div class="products" id="products">
        @php ($prodId = 0)
        @forelse($discount->products as $product)
            @php ($content = $product->forAdmin())
        <div class="item row">
            <span class="gray-title">Товар </span><button class="btn-delete"></button>
            <div class="col-md-4 col-xl-6">
                <div class="form-group">
                    <label>Фото</label>
                    <p>Загрузите фото товара</p>
                    <div class="files-input">
                        <img src="{{ $product->image ? asset('/storage/'.$product->image) : asset('/storage/no_image.jpg') }}"/>
                        {{ Form::file('product['.$prodId.'][image]', ['class' => $errors->has('image') ? 'form-control input-img is-invalid' : 'form-control input-img']) }}
                        <span class="btn-red">Добавить фото</span>
                        <span class="text">Или перетащите его сюда</span>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xl-6">
                <div class="form-group col-xl-8 pl-0">
                    @foreach ($languages as $lang)
                        {{ Form::label('title', 'Название') }}
                        <p>Укажите название товара </p>
                        {{ Form::text('product['.$prodId.']['.$lang->locale.'][title]', optional($content['product']->translate($lang->locale))->title, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control']) }}
                        @if($errors->has('title'))
                            <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="form-group col-xl-8 pl-0">
                    {{ Form::label('title', 'Url') }}
                    <p>Укажите Url товара </p>
                    {{ Form::text('product['.$prodId.'][slug]', $product->slug, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control']) }}
                </div>
                <div class="form-group col-xl-8 pl-0 units-select">
                    {{ Form::label('quantity', 'Количество и вес') }}
                    <p>Укажите количество или вес продукта (шт, л, кг)</p>
                    <div class="fields">
                        {{ Form::text('product['.$prodId.'][quantity]', $product->quantity, ['class' => $errors->has('quantity') ? 'form-control is-invalid' : 'form-control', 'placeholder'=>'Количество или вес']) }}

                        <div class="select">
                            {{ Form::select('['.$prodId.']product[unit]', ['sht' => 'шт', 'kg' => 'кг', 'l' => 'л', 'up' => 'уп'], $product->unit, ['class' => $errors->has('category') ? 'form-control is-invalid' : 'form-control']) }}
                        </div>
                    </div>
                </div>

                <div class="form-group pl-0 price-input">
                    <label>Цена</label>
                    <p>Укажите старую цену и размер скидки, новая цена посчитается автоматически</p>
                    <div class="fields">
                        {{ Form::text('product['.$prodId.'][price]', $product->price, ['class' => $errors->has('price') ? 'form-control price is-invalid' : 'form-control price', 'placeholder' => 'Старая цена грн']) }}
                        @if($errors->has('price'))
                            <span class="invalid-feedback">{{ $errors->first('price') }}</span>
                        @endif
                        {{ Form::text('product['.$prodId.'][discount]', $product->discount, ['class' => $errors->has('price') ? 'form-control discount is-invalid' : 'form-control discount', 'placeholder' => 'Размер скидки %']) }}
                        @if($errors->has('price'))
                            <span class="invalid-feedback">{{ $errors->first('discount') }}</span>
                        @endif
                        <span class="new-price">Новая цена: <span class="result"></span></span>
                    </div>
                </div>
            </div>
        </div>
        @php ($prodId++)
        @empty
            <span class="not-product">Нету товаров</span>
        @endforelse
    </div>
    <button class="btn btn-blue add-product" onclick="addProduct(); return false" type="submit">Добавить продукт</button>
</div>

@endif
{{-- Кнопки сохранения --}}
<div class="container">
    <div class="form-group">
        <div class="submit-btns">
            <button class="btn btn-red" type="submit">Добавить акцию</button>
            <button class="btn btn-white">Отмена</button>
        </div>
    </div>
</div>



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

    $('body').on('change', '.input-img', function(e) {
      handleImage(e);
    });

    $('.discount').on('chenge', function () {
        var newPrice,
            oldPrice,
            discount;
        oldPrice = parseInt($(this).prev('.price').val());
        discount = parseInt($(this).prev('.discount').val());
        newPrice = oldPrice - (oldPrice*discount/100);
        $('.new-price span').text(newPrice);
    });

  });
  function addIdShop(idShop, imgShop){
      var addHtml = '<div class="item">\n' +
          '            <button class="btn-delete" onclick="$(this).parent().remove()"></button>\n' +
          '              <input type="hidden" name="shop[]" value="'+idShop+'" >\n' +
          '            <div class="image"><img src="'+imgShop+'" alt=""></div>\n' +
          '        </div>';

      $('.filtered').append(addHtml);
  }
  var productId = {{ isset($prodId) ? $prodId : 0 }};
  //add product
  function addProduct() {
      $('.not-product').remove();
      var htmlProduct = '<div class="item row">';
      htmlProduct += '<span class="gray-title">Товар </span><button class="btn-delete"></button>';
      htmlProduct += '<div class="col-md-4 col-xl-6">';
      htmlProduct += '<div class="form-group">';
      htmlProduct += '<label>Фото</label>';
      htmlProduct += '<p>Загрузите фото товара</p>';
      htmlProduct += '<div class="files-input">';
      htmlProduct += '<img src=""/>';
      htmlProduct += '<input type="file" name="product['+productId+'][image]" class="form-control input-img">';
      htmlProduct += '<span class="btn-red">Добавить фото</span>';
      htmlProduct += '<span class="text">Или перетащите его сюда</span>';
      htmlProduct += '</div>';
      htmlProduct += '</div>';
      htmlProduct += '</div>';
      htmlProduct += '<div class="col-md-8 col-xl-6">';
      htmlProduct += '<div class="form-group col-xl-8 pl-0">';
      @foreach ($languages as $lang)
          htmlProduct += '<label for="title">Название</lebel>';
      htmlProduct += '<p>Укажите название товара </p>';
      htmlProduct += '<input type="text" name="product['+productId+'][{{ $lang->locale }}][title]" class="form-control">';
      @endforeach
          htmlProduct += '</div>';
      htmlProduct += '<div class="form-group col-xl-8 pl-0">';
      htmlProduct += '<label for="title">Url</lebel>';
      htmlProduct += '<p>Укажите Url товара </p>';
      htmlProduct += '<input type="text" name="product['+productId+'][slug]" class="form-control">';
      htmlProduct += '</div>';
      htmlProduct += '<div class="form-group col-xl-8 pl-0 units-select">';
      htmlProduct += '<label for="quantity">Количество и вес</lebel>';
      htmlProduct += '<p>Укажите количество или вес продукта (шт, л, кг)</p>';
      htmlProduct += '<div class="fields">';
      htmlProduct += '<input type="text" name="product['+productId+'][quantity]"  class="form-control" placeholder="Количество или вес">';
      htmlProduct += '<div class="select">';
      htmlProduct += '<select class="custom-select" name="product['+productId+'][unit]">';
      htmlProduct += '<option selected="selected" value="sht">шт</option>';
      htmlProduct += '<option value="kg">кг</option>';
      htmlProduct += '<option value="l">л</option>';
      htmlProduct += '<option value="up">уп</option>';
      htmlProduct += '</select>';
      htmlProduct += '</div>';
      htmlProduct += '</div>';
      htmlProduct += '</div>';
      htmlProduct += '<div class="form-group pl-0 price-input">';
      htmlProduct += '<label>Цена</label>';
      htmlProduct += '<p>Укажите старую цену и размер скидки, новая цена посчитается автоматически</p>';
      htmlProduct += '<div class="fields">';
      htmlProduct += '<input type="text" name="product['+productId+'][price]"  class="form-control price" placeholder="Старая цена грн">';
      htmlProduct += '<input type="text" name="product['+productId+'][discount]"  class="form-control discount" placeholder="Размер скидки %">';
      htmlProduct += '<span class="new-price">Новая цена: <span class="result"></span></span>';
      htmlProduct += '</div>';
      htmlProduct += '</div>';
      htmlProduct += '</div>';
      htmlProduct += '</div>';

      $('#products').append(htmlProduct);
      console.log(productId);
      productId++;
  }
</script>
@endpush