<template>
<main>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <TableComponent :data="streams" :loading="isLoading">
                            <template #header>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    Title
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Game
                                </th>
                                <th scope="col" class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    <span class="inline-block cursor-pointer ml-1" @click="sortStreams()">
                                        <SortAscendingIcon class="h-5 w-5 -mb-1.5 text-gray-400" aria-hidden="true" v-if="sortOrder === 'asc'" />
                                        <SortDescendingIcon class="h-5 w-5 -mb-1.5 text-gray-400" aria-hidden="true" v-else-if="sortOrder === 'desc'" />
                                    </span>
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
</main>
</template>

<script>
import TableComponent from '@/TableComponent.vue'
import {SortAscendingIcon, SortDescendingIcon} from '@heroicons/vue/solid'

export default {
    components: {
        SortAscendingIcon,
        SortDescendingIcon,
        TableComponent,
    },
    data: () => ({
        sortOrder: 'desc',
    }),
    computed: {
        streams() {
            return this.$store.getters['Streams/topStreams'];
        },
        isLoading() {
            return this.$store.getters['Streams/isLoading']
        },
    },
    async created() {
        await this.$store.dispatch('Streams/updateStreamTop', this.sortOrder)
    },
    methods: {
        async sortStreams() {
            let order = this.sortOrder === 'asc' ? 'desc' : 'asc'
            await this.$store.dispatch('Streams/updateStreamTop', order)
            this.sortOrder = order
        }
    },
}
</script>