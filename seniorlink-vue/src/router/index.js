import { createRouter, createWebHistory } from 'vue-router';

import AdminDashboard from '../components/SuperAdminDashboardComponent.vue';
import UpdateTown from '@/components/UpdateTown.vue';
import CreateTown from '@/components/CreateTownComponent.vue';


const routes = [
  {
    path: '/',
    name: 'Home', //change this later to landing page
    component: AdminDashboard
  },
  {
    path: '/update-town',
    name: 'UpdateTown',
    component: UpdateTown
  },
  {
    path: '/create-town',
    name: 'CreateTown',
    component: CreateTown,
  }
]

const router = createRouter({
    history: createWebHistory(process.env.VUE_APP_BASE_URL),
    routes
  });
  
  export default router;
