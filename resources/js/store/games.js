export default {
    state: {
        data: [],
        loading: true,
    },
    getters: {
        isLoading: (state) => state.loading,
        topGames: (state) => state.data,
    },
    mutations: {
        startLoading(state) {
            state.loading = true
        },
        updateTopGames(state, data) {
            state.data = data
            state.loading = false
        },
    },
    actions: {
        async updateGameTop({commit}) {
            try {
                commit('startLoading')
                let res = await axios.get('/api/games/top')
                commit('updateTopGames', res.data.data)
            } catch (err) {
            }
        },
    },
    namespaced: true,
}