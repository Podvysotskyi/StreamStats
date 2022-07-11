<template>
<main>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <TableComponent :data="games" :loading="isLoading">
                            <template #header>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    Game
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Streams
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Views
                                </th>
                            </template>

                            <template #row="{row}">
                                <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ row.name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ row.streams }}
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

export default {
    components: {
        TableComponent,
    },
    computed: {
        games() {
            return this.$store.getters['Games/topGames']
        },
        isLoading() {
            return this.$store.getters['Games/isLoading']
        },
    },
    async created() {
        await this.$store.dispatch('Games/updateGameTop')
    },
}
</script>