<template>
  <div id="app">
    <router-view>
    </router-view>
    <Player/>
  </div>
</template>

<script>
import Player from "@/components/player/Player"

export default {
  components: {
    Player
  },
  mounted: function () {
    if (this.$route.name !== 'connection' &&
        (localStorage.user === null ||
        localStorage.user === undefined ||
        localStorage.user === '')) {

      this.$router.push({name: 'connection'})
    }
  }
}
</script>
