<template>
  <div id="artist-field">
    <h3>Sélectionner un artiste</h3>
    <div class="field" v-if="selectExistingArtist">
      <div class="select-field">
        <select id="artist-id" v-model="artist" @change="selectArtist" required>
          <option :value="null" disabled>Choisissez un artiste</option>
          <option v-for="artist in artists" v-bind:key="artist.id" :value="artist.id">{{ artist.name }}</option>
        </select>
      </div>
      <a href="#" @click.prevent="toggleForm">+ ajouter</a>
    </div>
    <div class="field" v-else>
      <div class="sub-form">
        <input type="text" v-model="artistName" placeholder="Nom de l'artiste">
        <input type="file" @change="getFile">
        <a href="#" @click.prevent="createArtist">Créer</a>
      </div>
      <a href="#" @click.prevent="toggleForm">artistes existants</a>
    </div>
    <a href="#" class="next-step" @click.prevent="nextStep">Suivant</a>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest";

export default {
  name: "ArtistField",
  props: [ 'selectedArtist', 'artists' ],
  data: function () {
    return {
      artist: null,
      artistName: null,
      thumbnail: null,
      selectExistingArtist: true
    }
  },
  methods: {
    getFile: function (event) {
      this.thumbnail = event.target.files[0]
    },
    toggleForm: function () {
      this.selectExistingArtist = !this.selectExistingArtist
    },
    createArtist: async function () {
      const body = new FormData()
      body.append('thumbnail', this.thumbnail)
      body.append('name', this.artistName)

      const request = new FetchRequest('artists', 'POST', body, 'multipart/form-data')
      const response = await request.send()

      if (!response.error) {
        this.artist = response.artist.id
        this.selectArtist()
        this.$notify.success({
          title: 'Yay!',
          msg: 'Artiste crée avec succès'
        })
      } else
        this.$notify.error({
          title: 'Oops!',
          msg: response.message
        })
    },
    selectArtist: function () {
      this.$emit('artistselected', this.artist)
    },
    nextStep: function () {
      this.$emit('nextstep')
    }
  }
}
</script>

<style lang="scss" scoped>
#artist-field {
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
