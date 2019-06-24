<template>
  <footer class="footer">
    <div class="wrapper">
      <h3 class="footer__title">Давайте работать вместе!</h3>
      <p class="footer__subtitle">Заказывайте сейчас, а мы сделаем все остальное!</p>
      <div class="footer__wrapper">
        <form action="#" class="footer__form">
          <div class="footer__form-item">
            <label for="name" class="form__label">Как Вас зовут?</label>
            <input type="text" name="name" class="form__field" id="name" placeholder="Ваше Имя">
          </div>
          <div class="footer__form-item">
            <label for="tel" class="form__label">Ваш телефон</label>
            <input type="text" name="tel" class="form__field" id="tel" placeholder="+38">
          </div>
          <div class="footer__form-item">
            <label for="type-delivery" class="form__label">Вид перевозки</label>
            <select name="type-delivery" id="type-delivery" class="form__field">
              <option value="Вывоз мусора">Вывоз мусора</option>
              <option value="Вывоз мусора">Вывоз мусора</option>
              <option value="Вывоз мусора">Вывоз мусора</option>
              <option value="Вывоз мусора">Вывоз мусора</option>
              <option value="Вывоз мусора">Вывоз мусора</option>
            </select>
          </div>
          <div class="footer__form-item">
            <label for="type-delivery" class="form__label">Сообщение</label>
            <textarea placeholder="Расскажите нам историю..." class="form__field form__message" rows="8">

            </textarea>
          </div>
          <button class="button form-button">Заказать звонок</button>
        </form>
        <div class="footer__info">
          <p class="footer__info-title">Краун Карс<br>
            Мы работаем без выходных.<br>
          Прием заказов с 7:00 до 23:00</p>
          <p class="footer__info-block">
            Звоните:
            <a href="tel:0443627838" class="footer__tel">(044) 362 78 38</a>
            <a href="tel:0675588838" class="footer__tel">(067) 558 88 38</a>
            <a href="tel:0935188838" class="footer__tel">(093) 518 88 38</a>
            <a href="tel:0504588838" class="footer__tel">(050) 458 88 38</a>
          </p>
          <p class="footer__info-block">
            Пишите:  <br>
            <a href="mailto:gruzoperevozka.kiev@gmail.com" class="footer__tel">gruzoperevozka.kiev@gmail.com</a>
          </p>
          <p class="footer__info-block">
            Заходите: <br>
            г.Киев, ул.Волховская 10, офис 6
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>

  export default {
    name: 'Bottom'
  }
</script>