<template>
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                        <div class="px-2 py-3 sm:px-6" v-if="title">
                            {{ title }}
                        </div>
                        <div class="px-2 py-3 sm:px-6">
                            <slot></slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: null,
        },
    },
}
</script>