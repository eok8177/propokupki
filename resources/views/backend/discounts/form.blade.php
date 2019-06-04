<div class="container">

    <div class="form-group pl-0 col-md-4">
        <label>Магазин</label>
        <p>Выберите магазин в котором будет акция</p>

        <div class="search">
            <input type="text" placeholder="Введите название">
            <button class="btn-search"></button>
        </div>
    </div>
    {{ Form::hidden('shop[]', $discount->shops, []) }}
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

</div>
<hr>

{{-- Добавленые продукты, показывать при редактировании --}}
<div class="container">
    <div class="products" id="products">
        @if ($discount->products)
        @forelse ($discount->products as $product)
            <div class="item row">
                <span class="gray-title">Товар </span><button class="btn-delete"></button>
                <div class="col-md-4 col-xl-6">
                    <div class="form-group">
                        <label>Фото</label>
                        <p>Загрузите фото товара</p>
                        <div class="files-input">
                            <img src=""/>
                            <input type="file" class="form-control input-img" multiple="">
                            <span class="btn-red">Добавить фото</span>
                            <span class="text">Или перетащите его сюда</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-xl-6">
                    <div class="form-group col-xl-8 pl-0">
                        @foreach ($languages as $lang)
                            {{ Form::label('product_title', 'Название') }}
                            <p>Укажите название товара </p>
                            {{ Form::text($lang->locale.'[product_title]', '', ['class' => $errors->has('product_title') ? 'form-control is-invalid' : 'form-control']) }}
                            @if($errors->has('product_title'))
                                <span class="invalid-feedback">{{ $errors->first('product_title') }}</span>
                            @endif
                        @endforeach
                    </div>

                    <div class="form-group col-xl-8 pl-0 units-select">
                        {{ Form::label('product_quantity', 'Количество и вес') }}
                        <p>Укажите количество или вес продукта (шт, л, кг)</p>
                        <div class="fields">
                            {{ Form::text('product_quantity', $product->quantity, ['class' => $errors->has('product_quantity') ? 'form-control is-invalid' : 'form-control', 'placeholder'=>'Количество или вес']) }}

                            <div class="select">
                                <select class="custom-select" name="" id="">
                                    <option selected="selected" value="sht">шт</option>
                                    <option value="kg">кг</option>
                                    <option value="l">л</option>
                                    <option value="up">уп</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group pl-0 price-input">
                        <label>Цена</label>
                        <p>Укажите старую цену и размер скидки, новая цена посчитается автоматически</p>
                        <div class="fields">
                            {{ Form::text('product_price', $product->price, ['class' => $errors->has('product_price') ? 'form-control price is-invalid' : 'form-control price', 'placeholder' => 'Старая цена грн']) }}
                            @if($errors->has('product_price'))
                                <span class="invalid-feedback">{{ $errors->first('product_price') }}</span>
                            @endif
                            {{ Form::text('product_discount', $product->discount, ['class' => $errors->has('product_price') ? 'form-control discount is-invalid' : 'form-control discount', 'placeholder' => 'Размер скидки %']) }}
                            @if($errors->has('product_price'))
                                <span class="invalid-feedback">{{ $errors->first('product_discount') }}</span>
                            @endif
                            <span class="new-price">Новая цена: <span class="result"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
        @else
            <div class="item row">
                <span class="gray-title">Товар </span><button class="btn-delete"></button>
                <div class="col-md-4 col-xl-6">
                    <div class="form-group">
                        <label>Фото</label>
                        <p>Загрузите фото товара</p>
                        <div class="files-input">
                            <img src=""/>
                            <input type="file" class="form-control input-img" multiple="">
                            <span class="btn-red">Добавить фото</span>
                            <span class="text">Или перетащите его сюда</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-xl-6">
                    <div class="form-group col-xl-8 pl-0">
                        @foreach ($languages as $lang)
                            {{ Form::label('product_title', 'Название') }}
                            <p>Укажите название товара </p>
                            {{ Form::text($lang->locale.'[product_title]', $contents[$lang->locale]->product_title, ['class' => $errors->has('product_title') ? 'form-control is-invalid' : 'form-control']) }}
                            @if($errors->has('title'))
                                <span class="invalid-feedback">{{ $errors->first('product_title') }}</span>
                            @endif
                        @endforeach
                    </div>

                    <div class="form-group col-xl-8 pl-0 units-select">
                        {{ Form::label('product_quantity', 'Количество и вес') }}
                        <p>Укажите количество или вес продукта (шт, л, кг)</p>
                        <div class="fields">
                            {{ Form::text('product_quantity', $product->product_quantity, ['class' => $errors->has('product_quantity') ? 'form-control is-invalid' : 'form-control', 'placeholder'=>'Количество или вес']) }}
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
            </div>
        @endif
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
            <input type="file" class="form-control input-img" multiple="">
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

  });
</script>
@endpush