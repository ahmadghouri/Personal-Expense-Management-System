import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import Toast from 'vue-toastification'
import router from './router'
import 'vue-toastification/dist/index.css'
import { createPinia } from 'pinia'
import ClipLoader from 'vue-spinner/src/ClipLoader.vue'

const app = createApp(App);
app.use(Toast)
app.use(router)
app.component('ClipLoader', ClipLoader)
app.use(createPinia())
app.mount('#app')