<template>
<div class="bg-white overflow-hidden shadow divide-y divide-gray-300 rounded-lg">
    <div class="px-2 py-3 sm:px-6" v-if="title">
        {{ title }}
    </div>
    <table class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <slot name="header"></slot>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            <template v-if="loading">
                <tr>
                    <td colspan="1000" class="p-2">
                        Loading data...
                    </td>
                </tr>
            </template>
            <template v-else-if="data && data.length > 0">
                <tr v-for="(row, index) in data" v-show="!pagination || (index >= firstIndex && index < lastIndex)">
                    <slot name="row" :row="row"></slot>
                </tr>
            </template>
            <tr v-else>
                <tr>
                    <td colspan="1000" class="p-2">
                        Nothing found
                    </td>
                </tr>
            </tr>
        </tbody>
    </table>
    <div v-if="!loading && pagination && data.length > 0" class="flex px-4 py-3 items-center justify-between sm:px-6" aria-label="Pagination">
        <div class="hidden sm:block">
            <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">
                    {{ this.firstIndex + 1 }}
                </span>
                to
                <span class="font-medium">
                    {{ this.lastIndex }}
                </span>
                of
                <span class="font-medium">
                    {{ this.data.length }}
                </span>
                results
            </p>
        </div>
        <div class="flex-1 flex justify-between sm:justify-end" v-if="pageCount > 1">
            <button :class="['relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700', currentPage > 1 ? 'bg-white hover:bg-gray-50 cursor-pointer' : 'bg-gray-50']" @click="previousPage()">
                Previous
            </button>
            <button :class="['ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700', currentPage < pageCount ? 'bg-white hover:bg-gray-50 cursor-pointer' : 'bg-gray-50']" @click="nextPage()">
                Next
            </button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        loading: {
            type: Boolean,
            default: false,
        },
        pagination: {
            type: Boolean,
            default: true,
        },
        pageSize: {
            type: Number,
            default: 10,
        },
        data: {
            type: Array,
            default: [],
        },
        title: {
            type: String,
            default: null,
        },
    },
    data: () => ({
        currentPage: 1,
    }),
    computed: {
        firstIndex() {
            return this.pageSize * (this.currentPage - 1)
        },
        lastIndex() {
            let index = this.pageSize * this.currentPage
            return index > this.data.length ? this.data.length : index
        },
        pageCount() {
            return this.data ? Math.ceil(this.data.length / this.pageSize) : 0
        },
    },
    methods: {
        nextPage() {
            if (this.currentPage < this.pageCount) {
                this.currentPage++;
            }
        },
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
    },
    watch: {
        data() {
            this.currentPage = 1
        },
    },
}
</script>