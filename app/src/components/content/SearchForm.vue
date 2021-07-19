<template>
  <form @submit.prevent="search">
    <input type="text" v-model="searchValue" placeholder="Rechercher"/>
    <input type="submit" class="material-icons" value="search"/>
  </form>
</template>

<script>
export default {
  name: 'SearchForm',
  data: function () {
    return { searchValue: null }
  },
  methods: {
    search: function () {
      console.log(this.searchValue)
    }
  }
}
</script>

<style lang="scss" scoped>
form {
  input[type=text] {
    border: none;
    background: var(--white);
    padding: .7rem 1rem;
    border-radius: 100px;
    font-size: 1.2rem;
    width: 30rem;
    box-shadow: var(--box-shadow);
    opacity: .8;
    transition: opacity .3s ease;

    &:hover, &:focus {
      opacity: 1;
    }
  }

  input[type=submit] {
    position: absolute;
    transform: translateX(calc(-100% - .5rem)) translateY(4px);
    border: none;
    background: none;
    width: 2.3rem;
    height: 2.3rem;
    cursor: pointer;
  }
}
</style>
