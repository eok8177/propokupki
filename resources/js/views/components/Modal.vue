<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper" @click="$emit('close')">
        <div class="modal-container" @click.stop>

          <div class="modal-header">
            <button class="btn-delete" @click="$emit('close')"></button>
            <slot name="header">default header</slot>
          </div>

          <div class="modal-body">
            <slot name="body"></slot>
          </div>

          <div class="modal-footer">
            <slot name="footer">
              <button @click="$emit('close')">Close</button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
  export default {
    name: 'Modal',
    mounted() {
      document.body.addEventListener('keyup', e => {
        if (e.keyCode === 27) {
          this.$emit('close');
        }
      })
    }
  }
</script>

<style scoped lang=scss>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .2);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 100%;
  max-width: 392px;
  margin: 0px auto;
  padding: 0 30px;
  background-color: #fff;
  box-shadow: 0px 6px 6px rgba(0, 0, 0, 0.05);
  transition: all .3s ease;
  position: relative;
}

.modal-header {
  border-bottom: 1px solid #EBF1F5;
  padding: 24px 0;
}

.modal-body {
  padding: 20px 0;
}
.modal-footer {
  padding: 20px 0;
}

.btn-delete {
  position: absolute;
  right: 4px;
  top: 4px;
}

  /*
   * The following styles are auto-applied to elements with
   * transition="modal" when their visibility is toggled
   * by Vue.js.
   *
   * You can easily play with the modal transition by editing
   * these styles.
   */

   .modal-enter {
    opacity: 0;
  }

  .modal-leave-active {
    opacity: 0;
  }

  .modal-enter .modal-container,
  .modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }
</style>