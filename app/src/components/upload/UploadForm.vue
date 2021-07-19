<template>
  <form @submit.prevent="upload">
    <Steps @changestep="goToStep" :artistId="selectedArtist" :albumId="selectedAlbum" :currentStep="step"/>
    <ArtistField v-if="displayArtist" :artists="artists" :selectedArtist="selectedArtist" @artistselected="setArtist" @nextstep="nextStep"/>
    <AlbumField v-else-if="displayAlbum" :selectedAlbum="selectedAlbum" :artistId="selectedArtist" @albumselected="setAlbum" @nextstep="nextStep"/>
    <SongField v-else-if="displaySong" :artistId="selectedArtist" :albumId="selectedAlbum" @sendrequest="setSong"/>
  </form>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"
import Steps from "@/components/upload/Steps"
import ArtistField from "@/components/upload/ArtistField"
import AlbumField from "@/components/upload/AlbumField"
import SongField from "@/components/upload/SongField"

export default {
  name: "UploadForm",
  components: {
    ArtistField,
    AlbumField,
    SongField,
    Steps
  },
  data: function () {
    return {
      artists: null,
      selectedArtist: null,
      selectedAlbum: null,
      songTitle: '',
      songFile: null,
      step: 1
    }
  },
  mounted: async function () {
    this.artist = this.selectedArtist

    const request = new FetchRequest('artists')
    const response = await request.send()
    if (!response.error)
      this.artists = response.artists
    else
      this.$notify.error({
        title: 'Oops',
        msg: response.message
      })
  },
  methods: {
    upload: async function () {
      const body = new FormData()
      body.append('title', this.songTitle)
      body.append('song', this.songFile)
      body.append('album_id', this.selectedAlbum)
      body.append('artist_id', this.selectedArtist)

      const request = new FetchRequest('songs', 'POST', body, 'multipart/form-data')
      const response = await request.send()

      if (!response.error) {
        this.selectedArtist = null
        this.selectedAlbum = null
        this.songTitle = null
        this.songFile = null
        this.$notify.success({
          title: 'Yay!',
          msg: 'Morceaux crée avec succès'
        })
      } else {
        this.$notify.error({
          title: 'Oops!',
          msg: response.message
        })
      }
    },
    setArtist: function (artistId) {
      this.selectedArtist = artistId
    },
    setAlbum: function (albumId) {
      this.selectedAlbum = albumId
    },
    setSong: function (songValues) {
      this.songTitle = songValues.title
      this.songFile = songValues.file
    },
    nextStep: function () {
      this.step += 1
    },
    goToStep: function (targetStep) {
      this.step = targetStep
    }
  },
  computed: {
    selectedArtistId: function () {
      return this.selectedArtist
    },
    displayArtist: function () {
      return this.step === 1
    },
    displayAlbum: function () {
      return this.step === 2
    },
    displaySong: function () {
      return this.step === 3
    }
  }
}
</script>

<style lang="scss" scoped>
form {
  position: relative;
  margin-top: 2rem;
}
</style>
