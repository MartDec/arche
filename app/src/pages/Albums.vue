<template>
  <div class="wrapper">
    <Header/>
    <div class="background">
      <h2>Albums</h2>
      <SearchForm/>
    </div>
    <div class="albums-wrapper">
      <AlbumCard v-for="album in albums" v-bind:key="album.id" :id="album.id" :displayArtist="true"/>
    </div>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"
import Header from "@/components/content/Header"
import SearchForm from "@/components/content/SearchForm"
import AlbumCard from "@/components/albums/AlbumCard"

export default {
  name: 'Artists',
  components: {
    Header,
    SearchForm,
    AlbumCard
  },
  data: function () {
    return {
      albums: null
    }
  },
  mounted: async function () {
    const request = new FetchRequest('albums?get_artist=1&get_songs=1')
    const response = await request.send()
    if (!response.error)
      this.albums = response.albums
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
  width: calc(34rem);
  height: 390px;
  border-radius: 15px;
  left: 8rem;
  transform: translateY(3rem);
  box-shadow: 0px 0px 30px 0px rgba(50, 50, 50, 0.3);
  background-image: url("/img/albums.jpg");
  background-size: cover;
  padding: 2rem 0 0 2rem;
  z-index: 40;

  h2 {
    color: var(--white);
    font-size: 2rem;
    margin-bottom: 1rem;
  }
}

.albums-wrapper {
  position: relative;
  z-index: 50;
  top: 18rem;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}
</style>
