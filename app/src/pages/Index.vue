<template>
    <div class="wrapper">
      <div class="background"></div>
      <Header/>
      <LatestArtists/>
      <LatestAlbums/>
    </div>
</template>

<script>
import Header from '@/components/content/Header'
import LatestArtists from "@/components/artists/LatestArtists";
import LatestAlbums from "@/components/albums/LatestAlbums";

export default {
  name: 'Index',
  components: {
    Header,
    LatestArtists,
    LatestAlbums
  },
  mounted: function () {
    if (localStorage.user === undefined || localStorage.user === '')
      this.$router.push({ name: 'connection' })
  }
}
</script>

<style lang="scss" scoped>
.background {
  position: absolute;
  width: 510px;
  height: 720px;
  border-radius: 15px;
  right: -2rem;
  box-shadow: 0px 0px 30px 0px rgba(50, 50, 50, 0.3);
  background-image: url("../../public/img/backgrounds/index.jpg");
  background-size: cover;
}
</style>
