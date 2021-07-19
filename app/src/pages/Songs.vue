<template>
  <div class="wrapper">
    <Header/>
    <div class="background">
      <h2>Morceaux</h2>
      <SearchForm/>
    </div>
    <div class="songs-wrapper">
      <SongsList :songs="songs"/>
    </div>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"
import Header from "@/components/content/Header"
import SearchForm from "@/components/content/SearchForm"
import SongsList from "@/components/songs/SongsList"

export default {
  name: "Songs",
  components: {
    SearchForm,
    Header,
    SongsList
  },
  data: function () {
    return {
      songs: []
    }
  },
  mounted: async function () {
    const request = new FetchRequest('songs')
    const response = await request.send()

    if (!response.error)
      this.songs = response.songs
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
  height: 300px;
  width: 522px;
  border-radius: 15px;
  left: 8rem;
  transform: translateY(3rem);
  box-shadow: 0px 0px 30px 0px rgba(50, 50, 50, 0.3);
  background-image: url("/img/morceaux.jpg");
  background-size: cover;
  background-position: center;
  padding: 2rem 0 0 2rem;
  z-index: 40;

  h2 {
    color: var(--white);
    margin-bottom: 1rem;
  }
}

.songs-wrapper {
  position: relative;
  z-index: 50;
  margin-top: 25rem;
}
</style>