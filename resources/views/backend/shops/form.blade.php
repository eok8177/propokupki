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
                {{ Form::label('category', 'Категория') }}
                <p>Выберите подходящую категорию магазина</p>
                <div class="select">
                    {{ Form::select('category', [true => 'Yes', false => 'No'], $shop->category, ['class' => $errors->has('category') ? 'form-control is-invalid' : 'form-control']) }}
                    @if($errors->has('category'))
                        <span class="invalid-feedback">{{ $errors->first('category') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label>Логотип</label>
                <p>Загрузите логотип магазина</p>
                <div class="files-input">
                    <input type="file" class="form-control" multiple="">
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
                    <div class="image"><img src="/images/shop-1.jpg" alt=""></div>
                    <span class="title">Вэлыка кышеня</span>
                    <span class="desc">Электроника и быто…</span>
                    <hr>
                    <div class="status">
                        <span>Активный</span>
                        <label class="checkbox">
                            <input type="checkbox" checked="checked">
                            <span class="chk"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
