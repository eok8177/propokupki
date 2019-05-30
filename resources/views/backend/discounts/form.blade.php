<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group col-md-8 pl-0">
                @foreach ($languages as $lang)
                    {{ Form::label('title', 'Название') }}
                    <p>Укажите название магазина </p>
                    {{ Form::text($lang->locale.'[title]', $contents[$lang->locale]->title, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control']) }}
                    @if($errors->has('title'))
                        <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                    @endif
                @endforeach
            </div>
            <div class="form-group col-md-8 pl-0">
                {{ Form::label('slug', 'Силка') }}
                <p>Укажите силку магазина </p>
                {{ Form::text('slug', $shop->slug, ['class' => $errors->has('slug') ? 'form-control is-invalid' : 'form-control']) }}
                @if($errors->has('slug'))
                    <span class="invalid-feedback">{{ $errors->first('slug') }}</span>
                @endif
            </div>
            <div class="form-group col-md-8 pl-0">
                {{ Form::label('category', 'Категория') }}
                <p>Выберите подходящую категорию магазина</p>
                <div class="select">
                    {{ Form::select('category[]', $shop->categories_for_select, $shop->categories, ['class' => $errors->has('category') ? 'form-control is-invalid' : 'form-control']) }}
                    @if($errors->has('category'))
                        <span class="invalid-feedback">{{ $errors->first('category') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label>Логотип</label>
                <p>Загрузите логотип магазина</p>
                <div class="files-input">
                    <input type="file" class="form-control" name="image" multiple="">
                    <span class="btn-red">Добавить фото</span>
                    <span class="text">Или перетащите его сюда</span>
                </div>
            </div>

        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label>Превью</label>
                <p>Вид добавляемой карточки магазина</p>


                <div class="shop-item">
                    <div class="image"><img src="{{ asset('/storage/'.$shop->image) }}" alt=""></div>
                    <span class="title">{{ $contents[$lang->locale]->title }}</span>
                    <span class="desc">{{ $contents[$lang->locale]->title }}</span>
                    <hr>
                    <div class="status">
                        <span>Активный</span>
                        <label class="checkbox">
                            <input type="checkbox" name="status" value="1" checked="checked">
                            <span class="chk"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>

<div class="container">
    <div class="form-group">
        <label>Адреса</label>
        <p>Импорт или экспорт адресов из csv файла</p>
        <div class="addresses-input">
            @if ($method = 'create')
                <input type="file" name="import_file" />
            @else
                <div class="btn-group" role="group">
                    <button type="button" class="btn import">Импорт</button>
                    <button type="button" class="btn export">Экспорт</button>
                </div>
            @endif
            <span class="message">
            <span class="text">adresa-ashan-2019.csv</span>
            <span class="red">Добавлен</span>
          </span>
        </div>
    </div>

    <div class="form-group">
        <div class="submit-btns">
            <button class="btn btn-red" type="submit">Сохранить магазин</button>
            <a href="/admin/shops" class="btn btn-white">Отмена</a>
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