export default {
    state: {
        top: null,
        statistics: null,
        loading: true,
    },
    getters: {
        isLoading: (state) => state.loading,
        topStreams: (state) => state.top,
        statistics: (state) => state.statistics,
    },
    mutations: {
        startLoading(state) {
            state.loading = true
        },
        updateTopStreams(state, data) {
            state.top = data
            state.loading = false
        },
        updateStreamsStatistics(state, data) {
            state.statistics = data
            state.loading = false
        }
    },
    actions: {
        async updateStreamTop({commit}, order) {
            try {
                commit('startLoading')
                let res = await axios.get(`/api/streams/top?sort=${order}`)
                commit('updateTopStreams', res.data.data)
            } catch (err) {
            }
        },
        async updateStreamStatistics({commit}) {
            try {
                commit('startLoading')
                let res = await axios.get('/api/streams/statistics')
                commit('updateStreamsStatistics', res.data.data)
            } catch (err) {
            }
        },
    },
    namespaced: true,
}