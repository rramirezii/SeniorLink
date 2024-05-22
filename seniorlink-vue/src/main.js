import { createApp } from 'vue';
//views
// import App from './components/ViewTown.vue';
// import App from './components/ViewClientPerBarangay.vue';
// import App from './components/ViewBarangayPerTown.vue';
// import App from './components/ViewClientList';
// import App from './components/ViewBarangay.vue';
// import App from './components/ViewSuperAdmin.vue';
// import App from './components/ViewEstablishment.vue';
// import App from './components/ViewEstablishment-viewbutton.vue';
// import App from './components/ViewBarangay-wdelete.vue';
// import App from './components/ViewProductsTransaction.vue';

//dashboards
// import App from './views/SuperAdminDashboard.vue';
// import App from './views/ClientDashboard-Phone.vue';
import App from './views/ClientMain.vue';
// import App from './views/ClientDashboard.vue';
// import App from './views/BarangayDashboard.vue';
// import App from './views/EstablishmentDashboard.vue';
// import App from './views/TownDashboard.vue';

//senior/client-phone ver
// import App from './components/ClientSelectMenu.vue';
// import App from './components/ClientLogin.vue';
// import App from './components/ClientAuth.vue';
// import App from './components/ClientQR.vue';
// import App from './components/ClientProfile.vue';


//update
// import App from './components/UpdateBarangaySelect.vue';
// import App from './components/UpdateClientSelect.vue';
// import App from './components/UpdateTown.vue';
// import App from './components/UpdateTransaction.vue';
// import App from './components/UpdateTown.vue';
// import App from './components/UpdateTownSelf.vue';
// import App from './components/UpdateSuperAdmin.vue';
// import App from './components/UpdateEstablishment.vue';
// import App from './components/UpdateBarangay.vue';
// import App from './components/UpdateClient.vue';
// import App from './components/UpdateProduct.vue';
// import App from './components/UpdateProductTransaction.vue';

//create
// import App from './components/CreateTown.vue';

import api from './axios.config.js'; // Import your configured Axios instance

const app = createApp(App);

// Make the api instance globally available
app.config.globalProperties.$api = api; 

// Mount the app
app.mount('#app');
