<template>
  <div class="single-artist">
    <router-link :to="{ name: 'artist', params: { id: artist.id } }">
      <img :src="server + artist.thumbnail" alt="" />
    </router-link>
    <div class="artist-infos">
      <div class="artist-name">
        <h3>{{ artistName }}</h3>
        <router-link :to="{ name: 'artist', params: { id: artist.id } }">voir</router-link>
      </div>
      <div class="details">
        {{ artist.albums.length }} albums - {{ artist.songs.length }} morceaux
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ArtistCard',
  props: [ 'artist' ],
  computed: {
    artistName: function () {
      let artistName = this.artist.name
      if (artistName > 18)
        artistName = `${artistName.substring(0, 18).trim()}...`

      return artistName
    }
  }
}
</script>

<style lang="scss" scoped>
.single-artist {
  border-radius: 15px;
  background-color: var(--white);
  box-shadow: var(--box-shadow);
  height: 15rem;

  & > a {
    display: block;
    width: 100%;
    height: 60%;
    border-radius: 15px 15px 0 0;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 15px 15px 0 0;

      &:hover {
        -webkit-transform: scale(1.05);
        transform: scale(1.05);
      }
    }
  }

  .artist-infos {
    padding: .5rem 1rem;
    display: flex;
    flex-direction: column;
    justify-content: center;

    .artist-name {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: .5rem;

      h3 {
        font-size: 1.75rem;
      }
    }
  }
}
</style>
