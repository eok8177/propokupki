<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group col-md-8 pl-0">
                @foreach ($languages as $lang)
                    {{ Form::label('title', 'Название') }}
                    <p>Укажите название магазина </p>
                    {{ Form::text($lang->locale.'[title]', $contents[$lang->locale]->title, ['class' => $errors->has('title') ? 'post-title form-control is-invalid' : 'post-title form-control']) }}
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
                <div style="max-height: 628px;overflow: auto;">
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
            <div class="btn import">
              {{ Form::file('import_file') }}
              <span>CSV файл с адресами</span>
            </div>
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
  function seoUrlFill(string, lang='ua'){
      var delimiter = '-',
          keyword = $('input[name="slug"]'),
          abc={'ß':'ss','à':'a','á':'a','â':'a','ã':'a','ä':'a','å':'a','æ':'ae','ç':'c','è':'e','é':'e','ê':'e','ë':'e','ì':'i','í':'i','î':'i','ï':'i','ð':'d','ñ':'n','ò':'o','ó':'o','ô':'o','õ':'o','ö':'o','ő':'o','ø':'o','ù':'u','ú':'u','û':'u','ü':'u','ű':'u','ý':'y','þ':'th','ÿ':'y','α':'a','β':'b','γ':'g','δ':'d','ε':'e','ζ':'z','η':'h','θ':'8','ι':'i','κ':'k','λ':'l','μ':'m','ν':'n','ξ':'3','ο':'o','π':'p','ρ':'r','σ':'s','τ':'t','υ':'y','φ':'f','χ':'x','ψ':'ps','ω':'w','ά':'a','έ':'e','ί':'i','ό':'o','ύ':'y','ή':'h','ώ':'w','ς':'s','ϊ':'i','ΰ':'y','ϋ':'y','ΐ':'i','ş':'s','ı':'i','ç':'c','ü':'u','ö':'o','ğ':'g','а':'a','б':'b','в':'v','г':'g','д':'d','е':'e','ё':'yo','ж':'zh','з':'z','и':'i','й':'j','к':'k','л':'l','м':'m','н':'n','о':'o','п':'p','р':'r','с':'s','т':'t','у':'u','ф':'f','х':'h','ц':'c','ч':'ch','ш':'sh','щ':'sh','ъ':'','ы':'y','ь':'','э':'e','ю':'yu','я':'ya','є':'ye','і':'i','ї':'yi','ґ':'g','č':'c','ď':'d','ě':'e','ň':'n','ř':'r','š':'s','ť':'t','ů':'u','ž':'z','ą':'a','ć':'c','ę':'e','ł':'l','ń':'n','ó':'o','ś':'s','ź':'z','ż':'z','ā':'a','č':'c','ē':'e','ģ':'g','ī':'i','ķ':'k','ļ':'l','ņ':'n','š':'s','ū':'u','ž':'z','ө':'o','ң':'n','ү':'u','ә':'a','ғ':'g','қ':'q','ұ':'u','ა':'a','ბ':'b','გ':'g','დ':'d','ე':'e','ვ':'v','ზ':'z','თ':'th','ი':'i','კ':'k','ლ':'l','მ':'m','ნ':'n','ო':'o','პ':'p','ჟ':'zh','რ':'r','ს':'s','ტ':'t','უ':'u','ფ':'ph','ქ':'q','ღ':'gh','ყ':'qh','შ':'sh','ჩ':'ch','ც':'ts','ძ':'dz','წ':'ts','ჭ':'tch','ხ':'kh','ჯ':'j','ჰ':'h'};
      switch(lang){
          case'bg':
              abc['щ']='sht';abc['ъ']='a';
            break;
          case'ua'
                :abc['и']='y';
            break;
      }
      string = string.toLowerCase();
      for(var k in abc){
          string = string.replace(RegExp(k,'g'),abc[k]);
      }
      var alnum = (typeof(XRegExp)==='undefined') ? RegExp('[^a-z0-9]+','ig'): XRegExp('[^\\p{L}\\p{N}]+','ig');
      string = string.replace(alnum,delimiter);
      string = string.replace(RegExp('['+delimiter+']{2,}','g'),delimiter);
      string = string.replace(RegExp('(^'+delimiter+'|'+delimiter+'$)','g'),'');
      // if(keyword.length && keyword.val() == ''){
          keyword.val(string);
      // }
  }

    $(function() {
        $('.post-title').on('change', function(e) {
          seoUrlFill($(this).val(), 'ua');
        });
    });

</script>
@endpush