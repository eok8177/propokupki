@extends('cutup.admin.layout')

@section('content')

<div class="container">
  <h1 class="title-page">Новый магазин</h1>
</div>
<hr>

<div class="shop-edit">
  <form action="">

    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group col-md-8 pl-0">
            <label>Название</label>
            <p>Укажите название магазина </p>
            <input type="text" class="form-control" placeholder="Название">
          </div>

          <div class="form-group col-md-8 pl-0">
            <label>Категория</label>
            <p>Выберите подходящую категорию магазина</p>
            <div class="select">
              <select class="custom-select" name="" id="">
                <option selected="selected">Продукты</option>
                <option >Продукты</option>
                <option >Продукты</option>
              </select>
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
                  <input type="checkbox">
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
          <div class="btn-group" role="group">
            <button type="button" class="btn import">Импорт</button>
            <button type="button" class="btn export">Экспорт</button>
          </div>

          <span class="message">
            <span class="text">adresa-ashan-2019.csv</span>
            <span class="red">Добавлен</span>
          </span>
        </div>
      </div>

      <div class="form-group">
        <div class="submit-btns">
          <button class="btn btn-red" type="submit">Добавить магазин</button>
          <button class="btn btn-white">Отмена</button>
        </div>
      </div>

    </div>
  </form>


</div>


@endsection