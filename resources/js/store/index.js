import { createStore } from 'vuex'

import GameModule from './games'
import StreamModule from './streams'
import StatisticsModule from './statistics'

export default createStore({
    state: {
        user: null,
    },
    getters: {
        isAuthenticated: (state) => state.user !== null,
        user: (state) => state.user,
    },
    mutations: {
        updateUser(state, user) {
            state.user = user
        },
    },
    actions: {
        updateCsrfCookie() {
            return axios.get('/sanctum/csrf-cookie')
        },
        async updateUser({commit}) {
            try {
                let res = await axios.get('/api/user')
                commit('updateUser', res.data)
            } catch (err) {
            }
        },
    },
    modules: {
        Games: GameModule,
        Streams: StreamModule,
        Statistics: StatisticsModule,
    },
})