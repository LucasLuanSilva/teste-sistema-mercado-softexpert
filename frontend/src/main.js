import { createApp } from 'vue'
import './style.css'
import router from '../router'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.js'
import App from './App.vue'

const app = createApp(App)

app.use(router)

app.mount('#app')
