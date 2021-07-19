<template>
  <div class="wrapper">
    <Header/>
    <div class="background">
      <h2>Artistes</h2>
      <SearchForm/>
    </div>
    <div class="artist-wrapper">
      <ArtistCard v-for="artist in artists" v-bind:key="artist.id" :artist="artist"/>
    </div>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"
import Header from "@/components/content/Header"
import SearchForm from "@/components/content/SearchForm"
import ArtistCard from "@/components/artists/ArtistCard"

export default {
  name: 'Artists',
  components: {
    Header,
    SearchForm,
    ArtistCard
  },
  data: function () {
    return {
      artists: null
    }
  },
  mounted: async function () {
    const request = new FetchRequest('artists')
    const response = await request.send()
    if (!response.error)
      this.artists = response.artists
    else
      this.$notify.error({
        title: 'Oops',
        msg: response.message
      })
  }
}
</script>

<style lang="scss" scoped>
.background {
  position: absolute;
  width: 348px;
  height: 522px;
  border-radius: 15px;
  left: 8rem;
  transform: translateY(3rem);
  box-shadow: 0px 0px 30px 0px rgba(50, 50, 50, 0.3);
  background-image: url("/img/artistes.jpg");
  background-size: cover;
  padding: 2rem 0 0 2rem;
  z-index: 40;

  h2 {
    color: var(--white);
    font-size: 2rem;
    margin-bottom: 1rem;
  }
}

.artist-wrapper {
  position: relative;
  z-index: 50;
  top: 18rem;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}
</style>
