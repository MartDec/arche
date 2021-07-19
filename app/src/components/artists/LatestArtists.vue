<template>
  <div class="latest-artists">
    <LatestItemsHeader :title="'Derniers artistes'" :routeName="'artists'"/>
    <div class="artists-wrapper">
      <div v-for="artist in artists" :key="artist.id" class="single-artist">
        <router-link :to="{ name: 'artist', params: { id: artist.id } }">
          <img :src="server + artist.thumbnail" :alt="artist.name">
        </router-link>
        <div class="artist-infos">
          <div class="artist-name">
            <router-link :to="{ name: 'artist', params: { id: artist.id } }">{{ getArtistName(artist.name) }}</router-link>
          </div>
          <div>
            <span>
              <router-link :to="{ name: 'artist', params: { id: artist.id } }">{{ artist.albums.length }} albums</router-link> -
              <router-link :to="{ name: 'artist', params: { id: artist.id } }">{{ artist.songs.length }} morceaux</router-link>
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
  name: "LatestArtists",
  components: { LatestItemsHeader },
  data: function () {
    return {
      artists: []
    }
  },
  mounted: async function () {
    const request = new FetchRequest('artists?latest=1')
    const response = await request.send()
    if (!response.error)
      this.artists = response.artists
    else
      this.$notify.error({
        title: 'Oops!',
        msg: response.message
      })
  },
  methods: {
    getArtistName: function (artistName) {
      if (artistName.length > 18)
        artistName = `${artistName.substring(0, 18).trim()}...`

      return artistName
    }
  }
}
</script>

<style scoped lang="scss">
.latest-artists {
  position: relative;
  margin-top: 10rem;

  .artists-wrapper {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;

    .single-artist {
      background: var(--white);
      box-shadow: var(--box-shadow);
      border-radius: 15px;
      padding: 1rem;
      display: flex;
      align-items: center;

      & > a {
        display: block;
        width: 6rem;
        height: 6rem;
        border-radius: 50%;

        img {
          width: 100%;
          height: 100%;
          object-fit: cover;
          border-radius: 50%;
        }
      }

      .artist-infos {
        margin-left: 1rem;

        * {
          color: var(--black);
        }

        .artist-name {
          font-size: 1.5rem;
        }
      }
    }
  }
}
</style>
