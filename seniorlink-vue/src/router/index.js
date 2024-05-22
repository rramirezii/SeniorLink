import { createRouter, createWebHistory } from 'vue-router';
import * as views from "../view.js";

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

export default router