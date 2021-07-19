<template>
  <div class="wrapper">
    <Header/>
    <div class="album-wrapper">
      <div class="album-details">
        <img :src="albumThumbnail" :alt="albumTitle">
        <div class="infos">
          <form @submit.prevent="updateAlbum" v-show="displayTitleInput" action="#">
            <input type="text" ref="artistName" v-model="albumTitle">
            <input type="submit" value="modifier">
          </form>
          <h2 v-show="!displayTitleInput">{{ albumTitle }}</h2>
          <router-link :to="{ name: 'artist', params: { id: artistId }}">
            {{ artistName }}
          </router-link>
          <a @click.prevent="displayParams = !displayParams" href="#" class="material-icons">settings</a>
          <span class="params" :class="displayParams ? '' : 'd-none'">
            <a href="#" @click.prevent="displayAlbumForm">modifier</a>
            <a href="#" @click.prevent="deleteAlbum">supprimer</a>
          </span>
        </div>
      </div>
      <SongsList :songs="albumSongs"/>
    </div>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"
import Header from "@/components/content/Header"
import SongsList from "@/components/songs/SongsList"

export default {
  name: "Album",
  components: {
    Header,
    SongsList
  },
  data: function () {
    return {
      albumId: this.$route.params.id,
      album: {},
      displayParams: false,
      displayTitleInput: false
    }
  },
  mounted: async function () {
    const request = new FetchRequest(`albums/${this.albumId}`)
    const response = await request.send()
    if (!response.error)
      this.album = response.album
    else
      this.$notify.error({
        title: 'Oops',
        msg: response.message
      })
  },
  methods: {
    displayAlbumForm: function () {
      this.displayTitleInput = true
      this.displayParams = false
    },
    updateAlbum: async function () {
      this.displayTitleInput = false
      try {
        const request = new FetchRequest(`albums/${this.albumId}`, 'POST', { title: this.albumTitle })
        const response = await request.send()
        if (response.error)
          this.$notify.error({
            title: 'Oops!',
            msg: response.message
          })
        else
          this.$notify.success({
            title: 'Yay!',
            msg: 'Album modifié avec succès !'
          })
      } catch (error) {
        console.error(error)
      }
    },
    deleteAlbum: async function () {
      this.displayTitleInput = false
      const confirmation = confirm('Êtes-vous sûr de vouloir supprimer cet album ? Ses morceaux seront supprimés aussi')
      if (confirmation) {
        try {
          const request = new FetchRequest(`albums/${this.albumId}`, 'DELETE')
          await request.send()
          this.$notify.success({
            title: 'Yay!',
            msg: 'Album supprimé avec succès'
          })
          await this.$router.push({ name: 'index' })
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
    }
  },
  computed: {
    albumThumbnail: function () {
      return typeof this.album.thumbnail === 'undefined'
          ? null : this.server + this.album.thumbnail
    },
    albumTitle: {
      get: function () {
        return typeof this.album.title === 'undefined'
            ? null : this.album.title
      },
      set: function (newTitle) {
        this.album.title = newTitle
      }
    },
    artistId: function () {
      return typeof this.album.artist === 'undefined'
        ? null : this.album.artist.id
    },
    artistName: function () {
      return typeof this.album.artist === 'undefined'
          ? null : this.album.artist.name
    },
    albumSongs: function () {
      return typeof this.album.songs === 'undefined'
        ? [] : this.album.songs
    }
  }
}
</script>

<style lang="scss" scoped>
  .album-wrapper {
    margin-top: 4rem;

    .album-details {
      display: flex;
      align-items: center;
      margin-bottom: 4rem;

      img {
        width: 300px;
        height: 300px;
        border-radius: 15px;
        box-shadow: var(--box-shadow);
      }

      .infos {
        margin-left: 1.5rem;
        position: relative;

        h2, input {
          font-size: 4rem;
        }

        form {
          display: flex;

          input {
            display: block;
            background: transparent;
            border: none;

            &[type=text] {
              border-bottom: solid 2px var(--black);
              width: 50%;
            }

            &[type=submit] {
              font-size: 1.5rem;
              cursor: pointer;
              width: 25%;
            }
          }
        }

        & > a {
          display: block;
          font-size: 3rem;
          color: var(--black);

          &.material-icons {
            font-size: 1.5rem;
            display: inline;
          }
        }

        .params {
          position: absolute;
          left: 0;
          bottom: -2rem;
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
  }
</style>
