import { createApp } from 'vue';
import './bootstrap';
import './echo';
import 'flowbite'
import App from './components/App.vue'
import router from './router';
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
import VueApexCharts from 'vue3-apexcharts';


const app = createApp(App);
app.use(router)
app.use(ToastPlugin)
app.use(VueApexCharts)
app.mount('#app')
