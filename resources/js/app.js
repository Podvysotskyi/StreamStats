import './bootstrap';

import {createApp} from 'vue'
import App from './Pages/App.vue'
import store from './store'
import router from './router'

createApp(App).use(router).use(store).mount("#app")