import { createApp } from 'vue'
import Cart from './admin/views/Cart.vue'
import axios from 'axios'

axios.defaults.withCredentials = true

const app = createApp(Cart)
app.mount('#app')
