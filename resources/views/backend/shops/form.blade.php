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
                {{ Form::label('slug', 'Url') }}
                <p>Укажите url магазина </p>
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
                    <img src="{{ $shop->image ? asset('/storage/'.$shop->image) : asset('/storage/no_image.jpg') }}"/>
                    {{ Form::file('image', ['class' => $errors->has('image') ? 'form-control input-img is-invalid' : 'form-control input-img']) }}
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
                    <div class="image"><img src="{{ $shop->image ? asset('/storage/'.$shop->image) : asset('/storage/no_image.jpg') }}" alt="" class="preview-img"></div>
                    <span class="title">{{ $contents[$lang->locale]->title }}</span>
                    <span class="desc">{{ $contents[$lang->locale]->title }}</span>
                    <hr>
                    <div class="status">
                        <span>Активный</span>
                        <label class="checkbox">
                            {{ Form::checkbox('status', 1, ['class' => $errors->has('status') ? 'is-invalid' : '', 'checked' => 'checked']) }}
                            <span class="chk"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div>
                <label>Города:</label>
                <div>
                    @foreach($shop->cities as $city)
                        @php ($content = $city->forAdmin())
                        <span>{{ $content['sities']['ua']->title }}</span> <br>
                    @endforeach
                </div>
            </div>
            <div>
                <span></span>
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
                {{ Form::file('import_file') }}
            @else
                <div class="btn-group" role="group">
                    <button type="button" class="btn import">Импорт</button>
                    <button type="button" class="btn export">Экспорт</button>
                </div>
            @endif
            <span class="message">
{{--            <span class="text">adresa-ashan-2019.csv</span>--}}
{{--            <span class="red">Добавлен</span>--}}
          </span>
        </div>
    </div>

    <div class="form-group">
        <div class="submit-btns">
            {{ Form::submit('Сохранить магазин', ['class' => 'btn btn-red']) }}
            <a href="/admin/shops" class="btn btn-white">Отмена</a>
        </div>
    </div>
</div>


@push('scripts')
<script>
  $(function() {
    $('body').on('change', '.input-img', function(e) {
      handleImage(e);
    });

  });
</script>
@endpush