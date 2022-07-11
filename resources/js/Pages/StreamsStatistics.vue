<template>
<main>
    <PanelComponent v-if="!isLoading">
        Median number of viewers for all streams is <b>{{ statistics.median_viewers_count }}</b>
    </PanelComponent>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <TableComponent :data="statistics.data" :loading="isLoading" title="Streams by their start time">
                            <template #header>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    Date
                                </th>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    Start Time
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Streams
                                </th>
                            </template>

                            <template #row="{row}">
                                <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ row.date }}
                                </td>
                                <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ row.time }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    {{ row.streams }}
                                </td>
                            </template>
                        </TableComponent>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>
import TableComponent from '@/TableComponent.vue'
import PanelComponent from '@/PanelComponent.vue'

export default {
    components: {
        TableComponent, PanelComponent,
    },
    computed: {
        statistics() {
            return this.$store.getters['Statistics/streamsStatistics'];
        },
    },
    data: () => ({
        isLoading: true,
    }),
    async created() {
        await this.$store.dispatch('Statistics/updateStreamStatistics')
        this.isLoading = false
    },
}
</script>