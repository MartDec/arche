import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex)

const state = {
    playingList: [],
    song: null,
    audio: null,
    playing: false
}

const getters = {
    getPlayingList: state => {
        return state.playingList
    },
    getSongId: state => {
        return state.song.id
    },
    getSong: state => {
        return state.song
    },
    getAudio: state => {
        return state.audio
    },
    isPlaying: state => {
        return state.playing
    }
}

const mutations = {
    setPlayingList: (state, list) => {
        state.playingList = list
    },
    setSong: (state, song) => {
        state.song = song
    },
    setAudio: (state, audio) => {
        state.audio = audio
    },
    playAudio: state => {
        state.audio.play()
    },
    pauseAudio: state => {
        state.audio.pause()
    },
    setPlaying: (state, isPlaying) => {
        state.playing = isPlaying
    }
}

const actions = {
    setPlayingList: ({ commit }, list) => {
        commit('setPlayingList', list)
    },
    setSong: ({ commit }, song) => {
        commit('setSong', song)
    },
    setAudio: ({ commit }, audio) => {
        commit('setAudio', audio)
    },
    playAudio: ({ commit }) => {
        commit('playAudio')
        commit('setPlaying', true)
    },
    pauseAudio: ({ commit }) => {
        commit('pauseAudio')
        commit('setPlaying', false)
    }
}

export default new Vuex.Store({
    state,
    getters,
    mutations,
    actions
})