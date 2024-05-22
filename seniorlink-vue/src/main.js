import { createApp } from 'vue';
import * as views from "./view.js";
import { createRouter, createWebHistory } from 'vue-router';

import api from '../axios.config.js'; // Import your configured Axios instance

// import the routes
import routes from './router.js';

const router = createRouter({
    history: createWebHistory(),
    routes,
  });

const app = createApp(views.SuperAdminDashboard); //change this to landing page, the login

// Make the api instance globally available
app.config.globalProperties.$api = api; 

app.use(router);

// Mount the app
app.mount('#app');
