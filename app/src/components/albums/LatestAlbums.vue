<template>
  <div class="latest-albums">
    <LatestItemsHeader :title="'Derniers albums'" :routeName="'albums'"/>
    <div class="albums-wrapper">
      <div v-for="album in albums" :key="album.id" class="single-album">
        <router-link class="album-image" :to="{ name: 'album', params: { id: album.id }}">
          <img :src="server + album.thumbnail" :alt="album.title">
        </router-link>
        <div class="album-infos">
          <div class="album-title">
            <router-link :to="{ name: 'album', params: { id: album.id }}">{{ getAlbumTitle(album.title) }}</router-link>
          </div>
          <div>
            <span>
              <router-link :to="{ name: 'artist', params: { id: album.artist.id }}">
                {{ album.artist.name }}
              </router-link> -
              <router-link :to="{ name: 'album', params: { id: album.id }}">
                {{ album.songs.length }} morceaux
              </router-link>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"
import LatestItemsHeader from "@/components/content/LatestItemsHeader";

export default {
  name: 'LatestAlbums',
  components: { LatestItemsHeader },
  data: function () {
    return {
      albums: []
    }
  },
  mounted: async function () {
    const request = new FetchRequest('albums?latest=1')
    const response = await request.send()
    if (!response.error)
      this.albums = response.albums
    else
      this.$notify.error({
        title: 'Oops!',
        msg: response.message
      })
  },
  methods: {
    getAlbumTitle: function (albumTitle) {
      if (albumTitle.length > 18)
        albumTitle = `${albumTitle.substring(0, 18).trim()}...`

      return albumTitle
    }
  }
}
</script>

<style lang="scss">
.latest-albums {
  position: relative;
  margin-top: 6rem;

  .albums-wrapper {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;

    .single-album {
      background: var(--white);
      box-shadow: var(--box-shadow);
      border-radius: 15px;
      padding-right: 1rem;
      display: flex;
      align-items: center;

      .album-image {
        width: 6rem;
        height: 100%;
        margin-right: 1rem;
        border-radius: 15px 0 0 15px;

        img {
          border-radius: 15px 0 0 15px;
          width: 100%;
          height: 100%;
          object-fit: cover;
        }
      }

      .album-infos {
        * {
          color: var(--black);
        }

        .album-title {
          font-size: 1.5rem;
        }
      }
    }
  }
}
</style>
