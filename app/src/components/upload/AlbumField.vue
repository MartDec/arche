<template>
  <div id="album-field">
    <h3>Sélectionner un album</h3>
    <div class="field" v-if="selectExistingAlbum">
      <div class="select-field">
        <select id="album-id" v-model="album" @change="selectAlbum" required>
          <option :value="null" disabled>Choisissez un album</option>
          <option v-for="album in albums" v-bind:key="album.id" :value="album.id">{{ album.title }}</option>
        </select>
      </div>
      <a href="#" @click.prevent="toggleForm">+ ajouter</a>
    </div>
    <div class="field" v-else>
      <div class="sub-form">
        <input type="text" v-model="albumTitle" placeholder="Nom de l'album">
        <input type="file" @change="getFile">
        <a href="#" @click.prevent="createAlbum">Créer</a>
      </div>
      <a href="#" @click.prevent="toggleForm">albums existants</a>
    </div>
    <a href="#" class="next-step" @click.prevent="nextStep">Suivant</a>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest";

export default {
  name: "AlbumField",
  props: [ 'artistId', 'selectedAlbum' ],
  data: function () {
    return {
      albums: [],
      album: null,
      albumTitle: null,
      thumbnail: null,
      selectExistingAlbum: true
    }
  },
  mounted: async function () {
    this.album = this.selectedAlbum

    const request = new FetchRequest(`albums?artist=${this.artistId}`)
    const response = await request.send()
    if (!response.error)
      this.albums = response.albums
    else
      this.$notify.error({
        title: 'Oops',
        msg: response.message
      })
  },
  methods: {
    getFile: function (event) {
      this.thumbnail = event.target.files[0]
    },
    toggleForm: function () {
      this.selectExistingAlbum = !this.selectExistingAlbum
    },
    createAlbum: async function () {
      const body = new FormData()
      body.append('thumbnail', this.thumbnail)
      body.append('title', this.albumTitle)
      body.append('artist_id', this.artistId)

      const request = new FetchRequest('albums', 'POST', body, 'multipart/form-data')
      const response = await request.send()

      if (!response.error) {
        this.album = response.album.id
        this.selectAlbum()
        this.$notify.success({
          title: 'Yay!',
          msg: 'Album crée avec succès'
        })
      } else {
        this.$notify.error({
          title: 'Oops!',
          msg: response.message
        })
      }
    },
    selectAlbum: function () {
      this.$emit('albumselected', this.album)
    },
    nextStep: function () {
      this.$emit('nextstep')
    }
  }
}
</script>

<style lang="scss" scoped>
#album-field {
  width: 100%;

  h3 {
    margin-bottom: 1rem;
  }

  .field {
    width: 100%;
    display: flex;
    justify-content: space-between;

    & > a {
      margin-top: .5rem;
      height: 1rem;
    }

    .select-field {
      position: relative;
      margin-right: 1rem;
      display: inline;

      select {
        background: var(--white);
        border: solid 1px var(--black);
        appearance: none;
        padding: .5rem 8rem .5rem 1rem;
        border-radius: 100px;
        font-size: 1rem;
        cursor: pointer;
      }

      &:after {
        content: '\25BC';
        font-size: .7rem;
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
      }
    }

    .sub-form {
      * {
        display: block;

        &:not(:first-child) {
          margin-top: 1rem;
        }
      }

      input[type=text] {
        border: solid 1px var(--black);
        padding: .5rem 8rem .5rem 1rem;
        border-radius: 100px;
        font-size: 1rem;
        background: transparent;
      }

      a {
        font-family: var(--titleFont);
        color: var(--red);
        font-size: 1.5rem;
      }
    }
  }

  .next-step {
    text-align: center;
    margin-top: 3rem;
    display: block;
  }
}
</style>
