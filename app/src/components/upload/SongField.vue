<template>
  <div class="field">
    <h3>Ajouter un morceaux</h3>
    <input type="text" v-model="songTitle" placeholder="Nom du morceau" required>
    <input type="file" @change="getFile" required>
    <input type="submit" value="Upload" @click="validate">
  </div>
</template>

<script>
export default {
  name: "AlbumField",
  props: [ 'artistId', 'albumId' ],
  data: function () {
    return {
      selectedAlbum: null,
      selectExistingAlbum: true,
      songTitle: null,
      songFile: null
    }
  },
  methods: {
    getFile: function (event) {
      this.songFile = event.target.files[0]
    },
    validate: function () {
      this.$emit('sendrequest', {
        title: this.songTitle,
        file: this.songFile
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.field {
  input {
    display: block;

    &:not(:first-child) {
      margin-top: 1rem;
    }

    &[type=text] {
      border: solid 1px var(--black);
      padding: .5rem 8rem .5rem 1rem;
      border-radius: 100px;
      font-size: 1rem;
      background: transparent;
    }

    &[type=submit] {
      border: none;
      background: none;
      font-family: var(--titleFont);
      font-size: 1.5rem;
      color: var(--red);
      cursor: pointer;
      margin: 3rem auto 0 auto;
    }
  }
}
</style>
