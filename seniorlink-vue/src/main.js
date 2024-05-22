import { createApp } from 'vue';
import * as views from "./view.js";
import api from '../axios.config.js'; // Import your configured Axios instance

const app = createApp(views.SuperAdminDashboard); //change this to landing page, the login

// Make the api instance globally available
app.config.globalProperties.$api = api; 

// Mount the app
app.mount('#app');
