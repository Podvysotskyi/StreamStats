<template>
    <Disclosure as="header" class="bg-white shadow" v-slot="{ open }">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:divide-y lg:divide-gray-200 lg:px-8">
            <div class="relative h-16 flex justify-end">
                <div class="relative z-10 flex items-center lg:hidden">
                    <DisclosureButton class="rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Open menu</span>
                        <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
                <div class="hidden lg:relative lg:z-10 lg:ml-4 lg:flex lg:items-center" v-if="!isLoading">
                    <template v-if="isAuthenticated">
                        <div class="text-base font-medium text-gray-800">
                            {{ user.name }}
                        </div>
                        <div class="text-sm font-medium text-gray-500 ml-2">
                            ({{ user.email }})
                        </div>
                        <a href="/logout" class="ml-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Logout
                        </a>
                    </template>
                    <a href="/login/twitch" class="ml-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" v-else>
                        Login
                    </a>
                </div>
            </div>
            <nav class="hidden lg:py-2 lg:flex lg:space-x-8" aria-label="Global">
                <template v-for="item in navigation" :key="item.name">
                    <router-link :to="item.href" class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 rounded-md py-2 px-3 inline-flex items-center text-sm font-medium" v-if="!item.requireAuth || isAuthenticated">
                        {{ item.name }}
                    </router-link>
                </template>
            </nav>
        </div>

        <DisclosurePanel as="nav" class="lg:hidden" aria-label="Global">
            <div class="pt-2 pb-3 px-2 space-y-1">
                <template v-for="item in navigation" :key="item.name">
                    <router-link :to="item.href" class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 block rounded-md py-2 px-3 text-base font-medium" v-if="!item.requireAuth || isAuthenticated">
                        {{ item.name }}
                    </router-link>
                </template>
            </div>
            <div class="border-t border-gray-200 pt-4 pb-3">
                <div class="px-4 flex items-center">
                    <div class="ml-3" v-if="isAuthenticated">
                        <div class="text-base font-medium text-gray-800">
                            {{ user.name }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">
                            {{ user.email }}
                        </div>
                    </div>
                    <div class="ml-auto flex-shrink-0">
                        <a href="/logout" class="[ml-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" v-if="isAuthenticated">
                            Logout
                        </a>
                        <a href="/login/twitch" class="ml-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" v-else>
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </DisclosurePanel>
    </Disclosure>

    <ErrorComponet v-if="hasError" />
    <router-view v-else-if="!isLoading" />
</template>

<script>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import { mapGetters } from 'vuex'

import ErrorComponet from './Error.vue'

export default {
    components: {
        Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems,
        BellIcon, MenuIcon, XIcon,
        ErrorComponet,
    },
    computed: {
        ...mapGetters([
            'isAuthenticated', 'user',
        ]),
    },
    data: () => ({
        isLoading: true,
        hasError: false,
        navigation: [
            { name: 'Top games', href: '/games', requireAuth: false },
            { name: 'Top streams', href: '/streams', requireAuth: false },
            { name: 'Streams statistics', href: '/statistics/stream', requireAuth: false },
            { name: 'User statistics', href: '/statistics/user', requireAuth: true },
        ],
    }),
    async created() {
        await this.$store.dispatch('updateCsrfCookie')
        await this.$store.dispatch('updateUser')
        this.isLoading = false
 
        axios.interceptors.response.use((res) => res, () => {
            this.hasError = true
        })

        this.$watch(() => this.$route.path, () => {
            this.hasError = false
        })
    },
}
</script>