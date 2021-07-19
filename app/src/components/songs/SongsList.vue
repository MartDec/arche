<template>
  <div id="song-list">
    <div class="table-head">
      <span class="play"></span>
      <span class="title">Titre</span>
      <span class="album">Album</span>
      <span class="duration">Durée</span>
      <span class="creation-date">Date d'ajout</span>
      <span class="more"></span>
    </div>
    <div class="table-body">
      <SongItem v-for="(song, key) in songs"
                v-bind:key="song.id"
                :song="song"
                :songKey="key"
                @playaudio="loadPlaylist"/>
    </div>
  </div>
</template>

<script>
import SongItem from "@/components/songs/SongItem";
import FetchRequest from "@/classes/FetchRequest";

export default {
  name: 'SongsList',
  props: [ 'songs' ],
  components: { SongItem },
  methods: {
    loadPlaylist: async function (songKey) {
      const list = this.songs.slice(songKey)
      for (let key in list) {
        const request = new FetchRequest(`songs/${list[key].id}`)
        const response = await request.send()
        list[key] = response.song
      }
      this.$store.dispatch('setPlayingList', list)
    }
  }
}
</script>

<style lang="scss" scoped>
#song-list {
  width: 100%;
  margin-top: 2rem;

  .table-head {
    width: 100%;
    display: grid;
    grid-template-columns: .5fr 1.5fr 2fr .5fr 1fr .5fr;
  }

  .table-body {
    width: 100%;
  }
}
</style>
