<div class="container">

    <div class="form-group pl-0 col-md-4">
        <label>Магазин</label>
        <p>Выберите магазин в котором будет акция</p>

        <div class="search">
            <input type="text" id="shop_search" data-href="{{route('admin.shops.ajaxShops')}}" autocomplete="off" placeholder="Введите название">
            <button class="btn-search"></button>
            <div class="search-result"></div>
        </div>
    </div>

    <div class="filtered">
        @foreach($discount->shops as $shop)
            <div class="item">
                <button class="btn-delete delete-item" onclick="$(this).parent().remove()"></button>
                {{ Form::hidden('shop[]', $shop->id, []) }}
                <div class="image"><img src="{{ $shop->image ? asset('/storage/'.$shop->image) : asset('/storage/no_image.jpg') }}" alt=""></div>
            </div>
        @endforeach
    </div>

    <div class="form-group col-md-4 pl-0">
        @foreach ($languages as $lang)
            {{ Form::label('title', 'Название') }}
            <p>Укажите название акции </p>
            {{ Form::text($lang->locale.'[title]', $discount->translate($lang->locale)->title, ['class' => $errors->has('title') ? 'post-title form-control is-invalid' : 'post-title form-control']) }}
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
            <div class="date">{{ Form::text('date_start', Date::parse($discount->date_start)->format('Y-m-d'), ['class' => $errors->has('date_start') ? 'form-control is-invalid' : 'form-control']) }}</div>
            <div class="date">{{ Form::text('date_end', Date::parse($discount->date_end)->format('Y-m-d'), ['class' => $errors->has('date_end') ? 'form-control is-invalid' : 'form-control']) }}</div>
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
        <div class="item row delete-block" id="product_{{ $prodId }}">
            <span class="gray-title">Товар {{ $prodId + 1 }}</span><button class="btn-delete product-delete"></button>
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
                        {{ Form::textarea(
                          'product['.$prodId.']['.$lang->locale.'][title]',
                          optional($content['product']->translate($lang->locale))->title,
                          ['class' => $errors->has('title') ? 'product-title form-control is-invalid' : 'product-title  form-control',
                            'data-id' => $prodId]) }}
                        @if($errors->has('title'))
                            <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                        @endif
                    @endforeach
                </div>
                {{ Form::hidden('product['.$prodId.'][product_id]', $product->id, []) }}
                <div class="form-group col-xl-8 pl-0">
                    {{ Form::label('title', 'Url') }}
                    <p>Укажите Url товара </p>
                    {{ Form::text('product['.$prodId.'][slug]', $product->slug, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'id' => 'slug_'.$prodId]) }}
                </div>
                <div class="form-group col-xl-8 pl-0 units-select">
                    {{ Form::label('quantity', 'Количество и вес') }}
                    <p>Укажите количество или вес продукта (шт, л, кг)</p>
                    <div class="fields">
                        {{ Form::text('product['.$prodId.'][quantity]', $product->quantity, ['class' => $errors->has('quantity') ? 'form-control is-invalid' : 'form-control', 'placeholder'=>'Количество или вес']) }}

                        <div class="select">
                            {{ Form::select('product['.$prodId.'][unit]', ['sht' => 'шт', 'kg' => 'кг', 'l' => 'л', 'up' => 'уп'], $product->unit, ['class' => $errors->has('category') ? 'form-control is-invalid' : 'form-control']) }}
                        </div>
                    </div>
                </div>

                <div class="form-group pl-0 price-input">
                    <label>Цена</label>
                    <p>Укажите старую цену и размер скидки</p>
                    <div class="fields">
                        {{ Form::text('product['.$prodId.'][old_price]', $product->old_price, ['class' => $errors->has('old_price') ? 'form-control price is-invalid' : 'form-control price', 'placeholder' => 'Старая цена грн']) }}
                        @if($errors->has('price'))
                            <span class="invalid-feedback">{{ $errors->first('price') }}</span>
                        @endif
                        {{ Form::text('product['.$prodId.'][discount]', $product->discount, ['class' => $errors->has('discount') ? 'form-control discount is-invalid' : 'form-control discount', 'placeholder' => 'Размер скидки %']) }}
                        {{Form::hidden('product['.$prodId.'][of]',0)}}
                        {{ Form::label('of', 'От: ') }}
                        {{ Form::checkbox('product['.$prodId.'][of]', 1, $product->of) }}
                        @if($errors->has('price'))
                            <span class="invalid-feedback">{{ $errors->first('discount') }}</span>
                        @endif
                         {{ Form::text('product['.$prodId.'][price]', $product->price, ['class' => $errors->has('price') ? 'form-control price is-invalid' : 'form-control price', 'placeholder' => 'Новая цена грн']) }}
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
@else
<div class="container">
  <div class="addresses-input btn-group">
    <div class="btn import">
      {{ Form::file('import_file_products') }}
      <span>CSV файл с товарами</span>
    </div>
    <div class="btn import">
      {{ Form::file('import_file_images') }}
      <span>ZIP файл с картинками</span>
    </div>
  </div>
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
      language: "ru",
       format: 'yyyy-mm-dd'
    });

    $('body').on('change', '.input-img', function(e) {
      handleImage(e);
    });

  });
  function addIdShop(idShop, imgShop){
      var addHtml = '<div class="item">\n' +
          '            <button class="btn-delete delete-item" onclick="$(this).parent().remove()"></button>\n' +
          '              <input type="hidden" name="shop[]" value="'+idShop+'" >\n' +
          '            <div class="image"><img src="'+imgShop+'" alt=""></div>\n' +
          '        </div>';

      $('.filtered').append(addHtml);
  }
  var productId = {{ isset($prodId) ? $prodId : 0 }};
  //add product
  function addProduct() {
      $('.not-product').remove();
      var num_prod = productId + 1;
      var htmlProduct = '<div class="item row delete-block" id="prod_'+productId+'">';
      htmlProduct += '<span class="gray-title">Товар '+num_prod+'</span><button class="btn-delete product-delete"></button>';
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
          htmlProduct += '<label for="title">Название</label>';
      htmlProduct += '<p>Укажите название товара </p>';
      htmlProduct += '<textarea name="product['+productId+'][{{ $lang->locale }}][title]" class="form-control product-title" data-id="'+productId+'"></textarea>';
      @endforeach
          htmlProduct += '</div>';
      htmlProduct += '<div class="form-group col-xl-8 pl-0">';
      htmlProduct += '<label for="title">Url</label>';
      htmlProduct += '<p>Укажите Url товара </p>';
      htmlProduct += '<input type="text" name="product['+productId+'][slug]" class="form-control" id="slug_'+productId+'">';
      htmlProduct += '</div>';
      htmlProduct += '<div class="form-group col-xl-8 pl-0 units-select">';
      htmlProduct += '<label for="quantity">Количество и вес</label>';
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
      htmlProduct += '<input type="text" name="product['+productId+'][old_price]"  class="form-control price" placeholder="Старая цена грн">';
      htmlProduct += '<input type="text" name="product['+productId+'][discount]"  class="form-control discount" placeholder="Размер скидки %">';
      htmlProduct += '<input type="hidden" name="product['+productId+'][of]" value="0">';
      htmlProduct += '<label for="of">От: </label>';
      htmlProduct += '<input type="checkbox" name="product['+productId+'][of]">';
      htmlProduct += '<input type="text" name="product['+productId+'][price]"  class="form-control price" placeholder="Новая цена грн">';
      htmlProduct += '</div>';
      htmlProduct += '</div>';
      htmlProduct += '</div>';
      htmlProduct += '</div>';

      $('#products').append(htmlProduct);
      console.log(productId);
      productId++;
  }

  function seoUrlFill(string, lang='ua', keyword){
      var delimiter = '-',
          keyword = keyword,
          abc={'ß':'ss','à':'a','á':'a','â':'a','ã':'a','ä':'a','å':'a','æ':'ae','ç':'c','è':'e','é':'e','ê':'e','ë':'e','ì':'i','í':'i','î':'i','ï':'i','ð':'d','ñ':'n','ò':'o','ó':'o','ô':'o','õ':'o','ö':'o','ő':'o','ø':'o','ù':'u','ú':'u','û':'u','ü':'u','ű':'u','ý':'y','þ':'th','ÿ':'y','α':'a','β':'b','γ':'g','δ':'d','ε':'e','ζ':'z','η':'h','θ':'8','ι':'i','κ':'k','λ':'l','μ':'m','ν':'n','ξ':'3','ο':'o','π':'p','ρ':'r','σ':'s','τ':'t','υ':'y','φ':'f','χ':'x','ψ':'ps','ω':'w','ά':'a','έ':'e','ί':'i','ό':'o','ύ':'y','ή':'h','ώ':'w','ς':'s','ϊ':'i','ΰ':'y','ϋ':'y','ΐ':'i','ş':'s','ı':'i','ç':'c','ü':'u','ö':'o','ğ':'g','а':'a','б':'b','в':'v','г':'g','д':'d','е':'e','ё':'yo','ж':'zh','з':'z','и':'i','й':'j','к':'k','л':'l','м':'m','н':'n','о':'o','п':'p','р':'r','с':'s','т':'t','у':'u','ф':'f','х':'h','ц':'c','ч':'ch','ш':'sh','щ':'sh','ъ':'','ы':'y','ь':'','э':'e','ю':'yu','я':'ya','є':'ye','і':'i','ї':'yi','ґ':'g','č':'c','ď':'d','ě':'e','ň':'n','ř':'r','š':'s','ť':'t','ů':'u','ž':'z','ą':'a','ć':'c','ę':'e','ł':'l','ń':'n','ó':'o','ś':'s','ź':'z','ż':'z','ā':'a','č':'c','ē':'e','ģ':'g','ī':'i','ķ':'k','ļ':'l','ņ':'n','š':'s','ū':'u','ž':'z','ө':'o','ң':'n','ү':'u','ә':'a','ғ':'g','қ':'q','ұ':'u','ა':'a','ბ':'b','გ':'g','დ':'d','ე':'e','ვ':'v','ზ':'z','თ':'th','ი':'i','კ':'k','ლ':'l','მ':'m','ნ':'n','ო':'o','პ':'p','ჟ':'zh','რ':'r','ს':'s','ტ':'t','უ':'u','ფ':'ph','ქ':'q','ღ':'gh','ყ':'qh','შ':'sh','ჩ':'ch','ც':'ts','ძ':'dz','წ':'ts','ჭ':'tch','ხ':'kh','ჯ':'j','ჰ':'h'};
      console.clear();
      console.log(keyword);
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
      keyword.val(string);
  }

  $(function() {
      $('body').on('change', '.post-title', function(e) {
          seoUrlFill($(this).val(), 'ua', $('input[name="slug"]'));
      });

      $('body').on('change', '.product-title', function(e) {
          var id = $(this).data('id');
          seoUrlFill($(this).val(), 'ua', $('#slug_'+id));
      });

  });

  //Delete record
    $('.product-delete').on('click', function (e) {
        if (!confirm('Are you sure you want to delete?')) return false;
        e.preventDefault();
        console.log($(this));
        // return;
        if($(this).data('href')){
            $.ajax({
                type: 'DELETE',  // destroy Method
                url: $(this).data('href')
            }).done(function (data) {
                console.log(data);
                location.reload(true);
            });
        } else {
            $(this).parent('.delete-block').remove();
        }
    });

</script>
@endpush
