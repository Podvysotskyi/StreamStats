<template>
<main>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <TableComponent :data="statistics?.followed_top_streams" :loading="isLoading" title="Followed top streams">
                            <template #header>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    Title
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Game
                                </th>
                                <th scope="col" class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Views
                                </th>
                            </template>

                            <template #row="{row}">
                                <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ row.title }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    {{ row.game }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ row.views }}
                                </td>
                            </template>
                        </TableComponent>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <PanelComponent v-if="!isLoading">
        <template v-if="statistics.lowest_followed_stream_for_top > 0">
            The lowest viewer count following stream needs to gain <b>{{ statistics.lowest_followed_stream_for_top }}</b> views to make it into the top 1000 streams
        </template>
        <template v-else>
            All following streams have enough views to make it into the top 1000 streams
        </template>
    </PanelComponent>

    <PanelComponent title="Shared stream tags">
        <template v-if="isLoading">
            Loading...
        </template>
        <template v-else-if="statistics.shared_stream_tags.length > 0">
            <span v-for="tag in statistics.shared_stream_tags" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-2">
                {{ tag }}
            </span>
        </template>
        <template v-else>
            Nothing found
        </template>
    </PanelComponent>
</main>
</template>

<script>
import { mapGetters } from 'vuex'

import TableComponent from '@/TableComponent.vue'
import PanelComponent from '@/PanelComponent.vue'

export default {
    components: {
        TableComponent, PanelComponent,
    },
    computed: {
        ...mapGetters([
            'isAuthenticated',
        ]),
        statistics() {
            return this.$store.getters['Statistics/userStatistics'];
        },
    },
    data: () => ({
        isLoading: true,
    }),
    async created() {
        if (this.isAuthenticated) {
            await this.$store.dispatch('Statistics/updateUserStatistics')
            this.isLoading = false
        } else {
            this.$router.push('/error')
        }
    },
}
</script>