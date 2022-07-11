import {createRouter, createWebHashHistory} from 'vue-router'

import Error from './Pages/Error.vue'
import TopGames from './Pages/TopGames.vue'
import TopStreams from './Pages/TopStreams.vue'
import StreamsStatistics from './Pages/StreamsStatistics.vue'

let routes = [
    { path: '/', redirect: '/games' },
    { path: '/games', component: TopGames },
    { path: '/streams', component: TopStreams },
    { path: '/statistics/stream', component: StreamsStatistics },
    { path: '/:pathMatch(.*)*', component: Error },
]

export default createRouter({
    history: createWebHashHistory(),
    linkActiveClass: 'bg-gray-100 text-gray-900',
    routes,
})