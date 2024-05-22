import Vue from 'vue';
import VueRouter from 'vue-router';
import CreateTown from './components/CreateTown.vue';
import CreateEstablishment from './components/CreateEstablishment.vue';
import ViewTowns from './components/ViewTowns.vue';
import ViewBarangay from './components/ViewBarangay.vue';
import ViewClients from './components/ViewClients.vue';
import ViewSuperAdmin from './components/ViewSuperAdmin.vue';
import ViewEstablishment from './components/ViewEstablishment.vue';

Vue.use(VueRouter);

const routes = [
  { path: '/create-town', component: CreateTown },
  { path: '/create-establishment', component: CreateEstablishment },
  { path: '/view-towns', component: ViewTowns },
  { path: '/view-barangay', component: ViewBarangay },
  { path: '/view-clients', component: ViewClients },
  { path: '/view-super-admin', component: ViewSuperAdmin },
  { path: '/view-establishment/:id', component: ViewEstablishment, name: 'viewEstablishment' },
];

export default new VueRouter({ routes });