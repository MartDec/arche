<template>
  <div class="title">
    <router-link :to="{ name: 'index' }">
      <h1>Arche</h1>
    </router-link>
    <p>Your personnal music streaming app</p>
  </div>
</template>

<script>
export default {
  name: 'Title'
}
</script>

<style lang="scss">
.title {
  h1 {
    font-size: 3rem;
    color: var(--red);
  }
}
</style>
