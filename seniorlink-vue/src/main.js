import { createApp } from 'vue';
import * as views from "./view.js";
// import router from './router';
import { createRouter, createWebHistory } from 'vue-router';

import api from '../axios.config.js'; // Import your configured Axios instance

const app = createApp(views.SuperAdminDashboard); //change this to landing page, the login


const routes = [
    { path: '/', component: views.SuperAdminDashboard},
    { path: '/create-town', component: views.CreateTown },
    // { path: '/create-establishment', component: views.CreateEstablishment },
    { path: '/view-towns', component: views.ViewTown },
    { path: '/view-barangay', component: views.ViewBarangay },
    { path: '/view-clients', component: views.ViewClientList},
    { path: '/view-super-admin', component: views.ViewSuperAdmin },
    { path: '/view-establishment/:id', component: views.ViewEstablishment, name: 'viewEstablishment' },
    { path: '/update-town', component: views.UpdateTown},
    { path: '/update-establish', component: views.UpdateEstablishment},
    { path: '/update-super', component: views.UpdateSuperAdmin},
  ]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Make the api instance globally available
app.config.globalProperties.$api = api; 

app.use(router);

console.log(router);

// Mount the app
app.mount('#app');
