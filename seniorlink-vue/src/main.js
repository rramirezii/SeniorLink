import { createApp } from 'vue'
// import App from './App.vue' // for the landing
import App from './App.vue'
import router from '@/router'
import { globalMixin } from './mixins/globalMixin'

createApp(App).mixin(globalMixin).use(router).mount('#app')
