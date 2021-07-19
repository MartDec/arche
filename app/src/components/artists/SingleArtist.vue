<template>
  <div id="single-artist">
    <div class="title">
      <img :src="artistThumbnail" :alt="artistName">
      <div class="artist-name">
        <form @submit.prevent="updateArtist" v-show="displayNameInput" action="#">
          <input type="text" ref="artistName" v-model="artistName">
          <input type="submit" value="modifier">
        </form>
        <h2 v-show="!displayNameInput">{{ artistName }}</h2>
        <a href="#" @click.prevent="displayParams = !displayParams" class="material-icons">settings</a>
        <span class="params" :class="displayParams ? '' : 'd-none'">
          <a href="#" @click.prevent="displayArtistForm">modifier</a>
          <a href="#" @click.prevent="deleteArtist">supprimer</a>
        </span>
      </div>
    </div>
    <div class="artist-content artist-albums">
      <h4>Albums</h4>
      <div class="albums-wrapper">
        <AlbumCard v-for="album in artistAlbums" v-bind:key="album.id" :id="album.id" :displayArtist="false"/>
      </div>
    </div>
    <div class="artist-content artist-songs">
      <h4>Morceaux</h4>
      <SongsList :songs="artistSongs" />
    </div>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"
import AlbumCard from "@/components/albums/AlbumCard"
import SongsList from "@/components/songs/SongsList"

export default {
  name: 'SingleArtist',
  props: [ 'id' ],
  components: {
    AlbumCard,
    SongsList
  },
  data: function () {
    return {
      artist: { songs: {}},
      displayParams: false,
      displayNameInput: false
    }
  },
  methods: {
    displayArtistForm: function () {
      this.displayNameInput = true
      this.displayParams = false
    },
    deleteArtist: async function () {
      const confirmation = confirm('Êtes-vous sûr de vouloir supprimer cet artiste ?')
      if (confirmation) {
        try {
          const request = new FetchRequest(`artists/${this.artist.id}`, 'DELETE')
          await request.send()
          this.$notify.success({
            title: 'Yay!',
            msg: 'Artiste supprimé avec succès !'
          })
          await this.$router.push({name: 'index'})
        } catch (error) {
          if (typeof error.message !== 'undefined')
            this.$notify.error({
              title: 'Oops!',
              msg: error.message
            })
          else
            console.error(error)
        }
      }
      this.displayParams = false
    },
    updateArtist: async function () {
      this.displayNameInput = false
      try {
        const request = new FetchRequest(`artists/${this.artist.id}`, 'POST', { name: this.artistName })
        const response = await request.send()
        if (response.error)
          this.$notify.error({
            title: 'Oops',
            msg: response.message
          })
        else
          this.$notify.success({
            title: 'Yay!',
            msg: 'Artiste modifié avec succès !'
          })
      } catch (error) {
        console.error(error)
      }
    }
  },
  mounted: async function () {
    const request = new FetchRequest(`artists/${this.id}`)
    const response = await request.send()

    if (!response.error)
      this.artist = response.artist
    else
      this.$notify.error({
        title: 'Oops',
        msg: response.message
      })
  },
  computed: {
    artistName: {
      get: function () {
        return typeof this.artist.name === 'undefined'
            ? null : this.artist.name
      },
      set: function (name) {
        this.artist.name = name
      }
    },
    artistThumbnail: function () {
      return typeof this.artist.thumbnail === 'undefined'
          ? null : this.server + this.artist.thumbnail
    },
    artistAlbums: function () {
      return typeof this.artist.albums === 'undefined'
          ? [] : this.artist.albums
    },
    artistSongs: function () {
      return typeof this.artist.songs === 'undefined'
          ? [] : this.artist.songs
    }
  }
}
</script>

<style lang="scss" scoped>
#single-artist {
  position: relative;
  z-index: 50;

  .title {
    display: flex;
    align-items: center;
    margin-top: 6rem;
    margin-bottom: 2rem;

    img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      border-radius: 50%;
      box-shadow: var(--box-shadow);
      margin-right: 2rem;
    }

    .artist-name {
      position: relative;

      h2, input {
        font-size: 2.5rem;
      }

      form {
        display: flex;

        input {
          display: block;
          background: transparent;
          border: none;
          width: 25%;

          &[type=text] {
            border-bottom: solid 2px var(--black);
          }

          &[type=submit] {
            font-size: 1.5rem;
            cursor: pointer;
          }
        }
      }

      .params {
        position: absolute;
        left: 0;
        top: 2.5rem;
        transform: translateX(3rem);
        background: var(--white);
        box-shadow: var(--box-shadow);
        padding: .75rem;
        border-radius: 15px;
        width: 10rem;
        display: flex;
        flex-direction: column;
        text-align: right;

        &.d-none {
          display: none;
        }

        &:after {
          content: '';
          width: 0;
          height: 0;
          border-top: .5rem solid transparent;
          border-bottom: .5rem solid transparent;
          border-right: .5rem solid var(--white);
          position: absolute;
          left: -1rem;
          transform: translateX(100%);
        }
      }
    }
  }

  .artist-content {
    margin-top: 6rem;

    h4 {
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    .albums-wrapper {
      width: 100%;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
    }
  }
}
</style>
