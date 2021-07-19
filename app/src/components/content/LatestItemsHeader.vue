<template>
  <div class="section-title">
    <h2>{{ title }}</h2>
    <router-link :to="{ name: routeName }">voir tout</router-link>
  </div>
</template>

<script>
export default {
  name: "LatestItemsHeader",
  props: {
    title: String,
    routeName: String
  }
}
</script>

<style lang="scss" scoped>
.section-title {
  display: flex;
  justify-content: space-between;
  width: 30%;
  align-items: center;
  margin-left: 5rem;
  margin-bottom: 2rem;

  a { color: var(--red); }
}
</style>
