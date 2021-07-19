<template>
  <div class="single-song">
    <span class="action">
      <button class="material-icons" @click.prevent="playSong">{{ playBtnState }}</button>
    </span>
    <span>
      <form v-show="editSongTitle" @submit.prevent="updateSong">
        <input type="text" v-model="songDetails.title">
        <input type="submit" class="material-icons" value="edit">
      </form>
      <span v-show="!editSongTitle">
        {{ songDetails.title }}
      </span>
    </span>
    <span>
      <a href="#">{{ albumTitle }}</a>
    </span>
    <span>{{ songDetails.duration }}</span>
    <span>{{ createdAt }}</span>
    <span class="action">
      <button class="material-icons" @click.prevent="toggleParams">more_vert</button>
      <span class="params" v-if="displayParams">
        <a href="#" @click.prevent="displaySongEditor">modifier</a>
        <a href="#" @click.prevent="deleteSong">supprimer</a>
      </span>
    </span>
  </div>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"

export default {
  name: 'SongItem',
  props: [ 'song', 'songKey' ],
  data: function () {
    return {
      songDetails: {},
      months: [
        "janvier",
        "février",
        "mars",
        "avril",
        "mai",
        "juin",
        "juillet",
        "août",
        "septembre",
        "octobre",
        "novembre",
        "décembre",
      ],
      displayParams: false,
      editSongTitle: false
    }
  },
  mounted: async function () {
    const request = new FetchRequest(`songs/${this.song.id}`)
    const response = await request.send()

    if (!response.error)
      this.songDetails = response.song
    else
      this.$notify.error({
        title: 'Oops',
        msg: response.message
      })
  },
  methods: {
    displaySongEditor: function () {
      this.displayParams = false
      this.editSongTitle = true
    },
    updateSong: async function () {
      this.editSongTitle = false
      try {
        const request = new FetchRequest(`songs/${this.songDetails.id}`, 'POST', { title: this.songDetails.title })
        const response =  await request.send()
        if (response.error)
          this.$notify.error({
            title: 'Oops!',
            msg: response.message
          })
        else
          this.$notify.success({
            title: 'Yay!',
            msg: 'Morceau modifié avec succès !'
          })
      } catch (error) {
        console.error(error)
      }
    },
    deleteSong: async function () {
      const confirmation = confirm('Êtes-vous sûr de vouloir supprimer ce morceau ?')
      if (confirmation) {
        const request = new FetchRequest(`songs/${this.songDetails.id}`, 'DELETE')
        const response = await request.send()
        if (response.error)
          this.$notify.error({
            title: 'Oops!',
            msg: response.message
          })
        else {
          this.$notify.success({
            title: 'Yay!',
            msg: 'Morceau supprimé avec succès !'
          })
        }
      }
    },
    playSong: function () {
      if (this.$store.getters.getAudio !== null &&
          this.$store.getters.getAudio.paused &&
          this.$store.getters.getSongId === this.song.id) {

        this.$store.dispatch("playAudio")
        return
      }

      if (this.$store.getters.isPlaying) {
        this.$store.dispatch("pauseAudio")
        if (this.$store.getters.getSongId === this.song.id)
          return
      }

      this.$store.dispatch("setSong", this.songDetails)
      const audio = new Audio(this.server + this.song.file)
      this.$store.dispatch("setAudio", audio)
      this.$store.dispatch("playAudio")
      this.$emit('playaudio', this.songKey)
    },
    toggleParams: function () {
      this.displayParams = !this.displayParams
    }
  },
  computed: {
    createdAt: function () {
      const date = new Date(this.songDetails.created_at)
      return `${date.getDate()} ${this.months[date.getMonth()]} ${date.getFullYear()}`
    },
    playBtnState: function () {
      return (this.$store.getters.isPlaying && this.$store.getters.getSongId === this.songDetails.id)
          ? 'pause_circle_outline'
          : 'play_circle_outline'
    },
    albumTitle: function () {
      return typeof this.songDetails.album === 'undefined'
        ? null : this.songDetails.album.title
    }
  }
}
</script>

<style lang="scss" scoped>
.single-song {
  display: grid;
  grid-template-columns: .5fr 1.5fr 2fr .5fr 1fr .5fr;
  background: var(--white);
  box-shadow: var(--box-shadow);
  border-radius: 15px;
  padding: .7rem 0;
  margin-top: 1.5rem;

  & > span {
    display: flex;
    align-items: center;
  }

  form {
    display: flex;
    justify-content: space-between;
    align-items: center;

    input {
      background: transparent;
      border: none;

      &[type=text] {
        border-bottom: solid 1px var(--black);
        font-size: 1rem;
      }
    }
  }

  .action {
    justify-content: center;
    height: 100%;
    position: relative;

    .params {
      position: absolute;
      left: 0;
      top: -0.5rem;
      transform: translateX(-90%);
      background: var(--white);
      box-shadow: var(--box-shadow);
      padding: .75rem;
      border-radius: 15px;
      width: 10rem;

      a {
        display: block;
      }

      &:after {
        content: '';
        width: 0;
        height: 0;
        border-top: .5rem solid transparent;
        border-bottom: .5rem solid transparent;
        border-left: .5rem solid var(--white);
        position: absolute;
        right: 0;
        top: 1rem;
        transform: translateX(100%);
      }
    }
  }

  a {
    color: var(--black);
  }

  button {
    border: none;
    background: transparent;
    cursor: pointer;
  }
}
</style>
