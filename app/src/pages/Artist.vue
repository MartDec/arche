<template>
  <div class="wrapper">
    <Header/>
    <div class="background"></div>
    <SingleArtist :id="this.$route.params.id"/>
  </div>
</template>

<script>
import Header from "@/components/content/Header"
import SingleArtist from "@/components/artists/SingleArtist"

export default {
  name: 'Artist',
  components: {
    Header,
    SingleArtist
  }
}
</script>

<style lang="scss" scoped>
.background {
  position: absolute;
  right: 3rem;
  margin-top: 2rem;
  height: 522px;
  width: 348px;
  background-image: url("/img/artistes.jpg");
  border-radius: 15px;
  background-size: cover;
  box-shadow: var(--box-shadow);
}
</style>
