<template>
<div id="player-wrapper" v-if="playable">
  <div class="custom-player">
    <div class="song-details">
      <img :src="albumThumbnail" :alt="albumName">
      <div class="infos">
        <p class="title">{{ song.title }}</p>
        <p>
          <router-link :to="{ name: 'artist', params: { id: artistId }}">{{ artistName }}</router-link> -
          <router-link :to="{ name: 'album', params: { id: albumId }}">{{ albumName }}</router-link>
        </p>
      </div>
    </div>
    <div class="timeline">
      <div class="cursor" :style="{ left: `${cursorPos}%` }"></div>
    </div>
    <div class="controls">
      <button class="material-icons" @click.prevent="replaySong">skip_previous</button>
      <button class="material-icons" @click.prevent="togglePlayerState">{{ playerStateBtn }}</button>
      <button class="material-icons" @click.prevent="endSong">skip_next</button>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: "Player",
  data: function () {
    return {
      song: {},
      audio: null,
      cursorPos: 0
    }
  },
  mounted() {
    const _this = this
    this.$store.watch(this.isPlaying, () => {
      if (this.isPlaying) {
        this.$nextTick(function () {
          setInterval(function () {
            const currentTime = _this.$store.getters.getAudio.currentTime
            const duration = _this.$store.getters.getAudio.duration
            _this.cursorPos = (currentTime / duration) * 100
            if (currentTime === duration) {
              if (_this.$store.getters.getPlayingList.length > 1) {
                _this.$store.getters.getPlayingList.shift()
                const nextAudio = _this.$store.getters.getPlayingList[0]
                const audio = new Audio(_this.server + nextAudio.file)
                _this.$store.dispatch("setSong", nextAudio)
                _this.$store.dispatch("setAudio", audio)
                _this.$store.dispatch("playAudio")
              } else {
                _this.$store.getters.getAudio.currentTime = 0
                _this.$store.dispatch('pauseAudio')
              }
            }
          }, 100)
        })
      }
    })
    this.$store.watch(this.getSong, () => {
      this.song = this.getSong()
    })
  },
  methods: {
    getSong: function () {
      return this.$store.getters.getSong
    },
    isPlaying: function () {
      return this.$store.getters.isPlaying
    },
    togglePlayerState: function () {
      if (this.$store.getters.isPlaying)
        this.$store.dispatch('pauseAudio')
      else
        this.$store.dispatch('playAudio')
    },
    replaySong: function () {
      this.$store.getters.getAudio.currentTime = 0
    },
    endSong: function () {
      this.$store.getters.getAudio.currentTime = this.$store.getters.getAudio.duration
    }
  },
  computed: {
    playable: function () {
      return this.$store.getters.getAudio !== null
    },
    artistName: function () {
      return typeof this.song.artist === 'undefined'
        ? null : this.song.artist.name
    },
    artistId: function () {
      return typeof this.song.artist === 'undefined'
          ? null : this.song.artist.id
    },
    albumName: function () {
      return typeof this.song.album === 'undefined'
          ? null : this.song.album.title
    },
    albumId: function () {
      return typeof this.song.album === 'undefined'
          ? null : this.song.album.id
    },
    songFile: function () {
      return typeof this.song.file === 'undefined'
          ? null : this.server + this.song.file
    },
    albumThumbnail: function () {
      return typeof this.song.album === 'undefined'
          ? null : this.server + this.song.album.thumbnail
    },
    playerStateBtn: function () {
      return this.$store.getters.isPlaying ? 'pause' : 'play_arrow'
    }
  }
}
</script>

<style lang="scss" scoped>
#player-wrapper {
  margin-top: 2rem;

  .custom-player {
    width: 100%;
    max-width: 1200px;
    background: var(--white);
    position: fixed;
    left: 50%;
    transform: translateX(-50%);
    bottom: 1rem;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 15px;
    box-shadow: var(--box-shadow);

    .song-details {
      display: flex;
      align-items: center;
      width: 25%;
      overflow-x: hidden;
      position: relative;

      img {
        height: 4rem;
        width: 4rem;
        object-fit: cover;
        margin-right: 1rem;
        border-radius: 15px 0 0 15px;
      }

      .infos {
        p {
          white-space: nowrap;

          &.title {
            font-size: 1.3rem;
          }

          a {
            color: var(--black);
          }
        }
      }
    }

    .timeline {
      flex: 1;
      height: 2px;
      background: var(--black);
      margin: 0 1rem;
      position: relative;

      .cursor {
        width: .5rem;
        height: 2rem;
        background: var(--white);
        border: solid 1px black;
        top: 50%;
        transform: translateY(-50%);
        position: absolute;
      }
    }

    .controls {
      padding-right: 2rem;

      button {
        border: none;
        background: transparent;
        cursor: pointer;
      }
    }
  }

  audio {
    display: none;
  }
}
</style>
