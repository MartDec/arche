<template>
  <div class="menu-content" :class="itemsClass">
    <div class="link-decoration">
      <div class="background-image index show-bg"></div>
      <div class="background-image upload"></div>
      <div class="background-image artists"></div>
      <div class="background-image albums"></div>
      <div class="background-image morceaux"></div>
      <div class="background-image playlists"></div>
      <div class="background-image logout"></div>
    </div>
    <div class="links">
      <router-link :to="{ name: 'index' }" @mouseover.native="changeBackground" data-type="index" title="Accueil">Accueil</router-link>
      <router-link :to="{ name: 'upload' }" @mouseover.native="changeBackground" data-type="upload" title="Ajouter des morceaux">Upload</router-link>
      <router-link :to="{ name: 'artists' }" @mouseover.native="changeBackground" data-type="artists" title="Voir les artistes">Artistes</router-link>
      <router-link :to="{ name: 'albums' }" @mouseover.native="changeBackground" data-type="albums" title="Voir les albums">Albums</router-link>
      <router-link :to="{ name: 'songs' }" @mouseover.native="changeBackground" data-type="morceaux" title="Voir les morceaux">Morceaux</router-link>
      <a href="#" @mouseover="changeBackground" data-type="playlists" title="Voir les playlists">Playlists</a>
      <a href="#" @click.prevent="logout" @mouseover="changeBackground" data-type="logout" title="Se déconnecter">Déconnexion</a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MenuContent',
  props: [ 'itemsClass' ],
  data: function () {
    return {
      background: ''
    }
  },
  methods: {
    changeBackground: function (event) {
      let targetClass = event.target.getAttribute('data-type')
      document.querySelectorAll('.background-image').forEach(elem => {
        elem.classList.remove('show-bg')
      })
      document.querySelector(`.background-image.${targetClass}`).classList.add('show-bg')
    },
    logout: function () {
      localStorage.removeItem('user')
      this.$router.push({ name: 'connection' })
    }
  }
}
</script>

<style lang="scss" scoped>
.menu-content {
  display: flex;
  position: absolute;
  width: calc(100% - 15rem);
  height: 100vh;
  flex-wrap: wrap;
  justify-content: flex-end;
  align-items: center;
  top: -8rem;
  z-index: 101;
  transform: translateX(-50%);
  left: 50%;

  &:not(.hide) {
    display: none;
  }

  & > div {
    height: 100%;
  }

  .links {
    width: 40%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: right;
    opacity: 0;

    a {
      padding: 2rem;
      font-size: 1.5rem;
      font-family: var(--titleFont);
      transition: margin .3s ease-out, color .3s ease-out, border .3s ease-out;
      color: var(--black);
      border-bottom: solid 1px transparent;

      &:hover {
        margin-right: 1rem;
        color: var(--red);
        border-bottom: solid 1px var(--black);
      }
    }
  }

  .link-decoration {
    position: absolute;
    left: -200%;
    width: 100%;

    * {
      height: 100%;
      width: 100%;
      background-size: cover;
      background-position: center;
      position: absolute;
      transition: opacity .3s ease;

      &.index {
        background-image: url('/img/home.jpg');
        z-index: 1;
        opacity: 0;
      }

      &.upload {
        background-image: url('/img/upload.jpg');
        z-index: 2;
        opacity: 0;
      }

      &.artists {
        background-image: url('/img/artistes.jpg');
        z-index: 3;
        opacity: 0;
      }

      &.albums {
        background-image: url('/img/albums.jpg');
        z-index: 4;
        opacity: 0;
      }

      &.morceaux {
        background-image: url('/img/morceaux.jpg');
        z-index: 5;
        opacity: 0;
      }

      &.playlists {
        background-image: url('/img/playlist.jpg');
        z-index: 6;
        opacity: 0;
      }

      &.logout {
        background-image: url('/img/logout.jpg');
        z-index: 6;
        opacity: 0;
      }

      &.show-bg {
        opacity: 1;
      }
    }
  }

  &.show {
    display: flex;

    .link-decoration {
      animation-name: displayMenuDecoration;
      animation-delay: .7s;
      animation-duration: .7s;
      animation-iteration-count: 1;
      animation-fill-mode: forwards;
    }

    .links {
      animation-name: displayMenuItem;
      animation-delay: .7s;
      animation-duration: .7s;
      animation-iteration-count: 1;
      animation-fill-mode: forwards;
    }
  }

  &.hide {
    animation-name: hideFullMenu;
    animation-delay: .7s;
    animation-duration: 0;
    animation-iteration-count: 1;
    animation-fill-mode: forwards;

    .link-decoration {
      animation-name: hideMenuDecoration;
      animation-delay: 0;
      animation-duration: .7s;
      animation-iteration-count: 1;
      animation-fill-mode: forwards;
    }

    .links {
      animation-name: hideMenuItem;
      animation-delay: 0s;
      animation-duration: .7s;
      animation-iteration-count: 1;
      animation-fill-mode: forwards;
    }
  }
}

@keyframes displayMenuDecoration {
  from { left: -200% }
  to { left: -40%; }
}

@keyframes hideMenuDecoration {
  from { left: -40% }
  to { left: -200%; }
}

@keyframes displayMenuItem {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes hideMenuItem {
  from { opacity: 1; }
  to { opacity: 0; }
}

@keyframes hideFullMenu {
  from { z-index: 101; }
  to { z-index: -1; }
}
</style>
