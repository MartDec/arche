<template>
  <div class="header">
    <Title/>
    <MenuOpener @openMenu="displayMenuContent" @closeMenu="hideMenuContent"/>
    <MenuContent :itemsClass="menuItemsClasses" @menuitemclicked="hideMenuContent"/>
  </div>
</template>

<script>
import Title from "@/components/content/Title"
import MenuOpener from "@/components/nav/MenuOpener"
import MenuContent from "@/components/nav/MenuContent"

export default {
  name: 'Header',
  components: {
    Title,
    MenuOpener,
    MenuContent
  },
  data: function () {
    return {
      showMenu: null,
      publicPath: this.$publicPath,
      background: ''
    }
  },
  mounted: function () {
    document.querySelector('#app').classList.remove('overflow-hidden')
  },
  methods: {
    displayMenuContent: function () {
      document.querySelector('#app').classList.add('overflow-hidden')
      this.showMenu = true
    },
    hideMenuContent: function () {
      setTimeout(() => {
        document.querySelector('#app').classList.remove('overflow-hidden')
      }, 1300)
      this.showMenu = false
    }
  },
  computed: {
    menuItemsClasses: function () {
      let className = null
      if (this.showMenu === true)
        className = 'show'
      else if (this.showMenu === false)
        className = 'hide'

      return className
    }
  }
}
</script>

<style lang="scss">
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  position: relative;
  margin-top: 3rem;
  padding: 0 3rem;
  max-width: 1200px;
}
</style>
