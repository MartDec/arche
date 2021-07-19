<template>
  <div class="nav-wrapper" :class="menuClass">
    <a v-if="!open" @click.prevent="openMenu" class="open-nav-btn">
      <span>me</span><span>nu</span>
    </a>
    <a v-else @click.prevent="closeMenu" class="close-nav-btn">
      <span class="material-icons">close</span>
    </a>
  </div>
</template>

<script>
export default {
  name: 'Menu',
  data: function () {
    return {
      open: null,
    }
  },
  methods: {
    openMenu: function () {
      this.open = true
      this.$emit('openMenu')
    },
    closeMenu: function () {
      this.open = false
      this.$emit('closeMenu')
    }
  },
  computed:  {
    menuClass: function () {
      let className = null
      if (this.open === true)
        className = 'open'
      else if (this.open === false)
        className = 'hide'

      return className
    }
  }
}
</script>

<style lang="scss">
.nav-wrapper {
  width: 4rem;
  height: 4rem;
  position: absolute;
  right: 3rem;
  top: 1rem;
  z-index: 100;

  &:before {
    content: '';
    border-radius: 50%;
    width: 100%;
    height: 100%;
    display: block;
    position: absolute;
    background: white;
    top: 0;
    left: 0;
    z-index: -1;
    transition: transform 600ms ease;
    transform: scale(0);
  }

  &.open:before {
    animation-name: showMenu;
    animation-delay: 0s;
    animation-duration: .6s;
    animation-iteration-count: 1;
    animation-fill-mode: forwards;
  }

  &.hide:before {
    transform: scale(65);
    animation-name: hideMenu;
    animation-delay: .6s;
    animation-duration: .6s;
    animation-iteration-count: 1;
    animation-fill-mode: forwards;
  }

  .open-nav-btn {
    width: 4rem;
    height: 4rem;
    text-align: center;
    text-transform: uppercase;
    color: var(--red);
    display: flex;
    flex-direction: column;
    font-size: 1.5rem;

    span {
      font-family: var(--titleFont);
    }
  }

  .close-nav-btn {
    display: block;
    width: 4rem;
    height: 4rem;
    text-align: center;

    span {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      height: 100%;
      color: var(--red);
    }
  }
}

@keyframes showMenu {
  from { transform: scale(0); }
  to { transform: scale(65); }
}

@keyframes hideMenu {
  from { transform: scale(65); }
  to { transform: scale(0); }
}
</style>
