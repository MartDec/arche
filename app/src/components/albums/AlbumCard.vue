<template>
  <div class="single-album">
    <router-link class="album-cover" :to="{ name: 'album', params: { id: album.id }}">
      <img :src="albumThumbnail" :alt="alt">
    </router-link>
    <div class="album-infos">
      <h3>
        <router-link :to="{ name: 'album', params: { id: album.id }}">{{ album.title }}</router-link>
      </h3>
      <div class="details">
        <div v-if="displayArtist">
          <router-link :to="{ name: 'artist', params: { id: album.artist.id }}">{{ album.artist.name }}</router-link>
        </div>
        <div>
          <router-link :to="{ name: 'album', params: { id: album.id }}">
            {{ songs.length }} morceaux
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"

export default {
  name: 'AlbumCard',
  props: [ 'id', 'displayArtist' ],
  data: function () {
    return {
      album: { artist: {}}
    }
  },
  mounted: async function () {
    if (this.album.songs === undefined || this.album.artist === undefined) {
      const request = new FetchRequest(`albums/${this.id}`)
      const response = await request.send()

      if (!response.error)
        this.album = response.album
      else
        this.$notify.error({
          title: 'Oops',
          msg: response.message
        })
    }
  },
  computed: {
    alt: function () {
      return this.displayArtist
        ? `${this.album.title} de ${this.album.artist.name}`
        : this.album.title
    },
    songs: function () {
      return typeof this.album.songs === 'undefined'
          ? []
          : this.album.songs
    },
    albumThumbnail: function () {
      return typeof this.album.thumbnail === 'undefined'
        ? null : this.server + this.album.thumbnail
    }
  }
}
</script>

<style lang="scss" scoped>
.single-album {
  border-radius: 15px;
  background-color: var(--white);
  box-shadow: var(--box-shadow);
  display: flex;
  padding: 1rem;

  .album-cover {
    width: 8rem;
    height: 8rem;
    margin-right: 1rem;
    display: block;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  .album-infos {
    h3 a {
      color: var(--black);
    }

    .details {
      margin-top: .5rem;

      a {
        color: var(--black);
      }
    }
  }
}
</style>
