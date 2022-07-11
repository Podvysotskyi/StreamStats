import axios from "axios"

export default {
    state: {
        streamsStatistics: null,
        userStatistics: null,
    },
    getters: {
        streamsStatistics: (state) => state.streamsStatistics,
        userStatistics: (state) => state.userStatistics,
    },
    mutations: {
        updateStreamsStatistics(state, data) {
            state.streamsStatistics = data
        },
        updateUsersStatistics(state, data) {
            state.userStatistics = data
        },
    },
    actions: {
        async updateStreamStatistics({commit}) {
            try {
                let res = await axios.get('/api/streams/statistics')
                commit('updateStreamsStatistics', res.data)
            } catch (err) {}
        },
        async updateUserStatistics({commit}) {
            try {
                let res = await axios.get('/api/user/statistics')
                commit('updateUsersStatistics', res.data)
            } catch (err) {}
        },
    },
    namespaced: true,
}