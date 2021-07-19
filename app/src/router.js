import VueRouter from 'vue-router'

import Index from "@/pages/Index"
import Connection from "@/pages/Connection"
import Upload from "@/pages/Upload"
import Artists from "@/pages/Artists"
import Artist from "@/pages/Artist"
import Albums from "@/pages/Albums"
import Album from "@/pages/Album"
import Songs from "@/pages/Songs"

const router = new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        {
            name: 'index',
            path: '/',
            component: Index
        },
        {
            name: 'connection',
            path: '/login',
            component: Connection
        },
        {
            name: 'upload',
            path: '/upload',
            component: Upload
        },
        {
            name: 'artists',
            path: '/artists',
            component: Artists
        },
        {
            name: 'artist',
            path: '/artists/:id',
            component: Artist
        },
        {
            name: 'albums',
            path: '/albums',
            component: Albums
        },
        {
            name: 'album',
            path: '/albums/:id',
            component: Album
        },
        {
            name: 'songs',
            path: '/songs',
            component: Songs
        }
    ]
})

export default router
