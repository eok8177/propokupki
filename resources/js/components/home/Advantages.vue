<template>
  <div class="advantages">
    <div class="wrapper">
      <h3 class="section-title advantages__title">Преимущества перевозки<br> грузов компанией Краун Карс</h3>
      <ul class="advantages__items">
        <li class="advantages__item advantages__item1">Большой выбор машин любого тоннажа</li>
        <li class="advantages__item advantages__item2">Доступные цены на перевозку грузов</li>
        <li class="advantages__item advantages__item3">Быстрая и качественная доставка грузов</li>
        <li class="advantages__item advantages__item4">Бережное отношение грузчиков к мебели и вещам</li>
        <li class="advantages__item advantages__item5">Помощь квалифицированных менеджеров в планировании переезда
        </li>
        <li class="advantages__item advantages__item6">Удобный заказ и быстрая подача машины</li>
      </ul>
    </div>
  </div>
</template>

<script>

  export default {
    name: 'Advantages',
    props: ['test'],
    mounted() {
      console.log(this.test);
    }
  }
</script>