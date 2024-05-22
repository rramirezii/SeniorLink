import { createApp } from 'vue';
//views
// import App from './views/ViewTown.vue';
// import App from './views/ViewClientPerBarangay.vue';
// import App from './views/ViewBarangayPerTown.vue';
// import App from './views/ViewClientList';
// import App from './views/ViewBarangay.vue';
// import App from './views/ViewSuperAdmin.vue';
// import App from './views/ViewEstablishment.vue';
// import App from './views/ViewEstablishment-viewbutton.vue';
// import App from './views/ViewBarangay-wdelete.vue';
// import App from './views/ViewProductsTransaction.vue';

//dashboards
import App from './views/SuperAdminDashboard.vue';

//senior/client-phone ver
// import App from './views/ClientMain.vue';
// import App from './views/ClientSelectMenu.vue';
// import App from './views/ClientLogin.vue';
// import App from './views/ClientAuth.vue';
// import App from './views/ClientQR.vue';
// import App from './views/ClientProfile.vue';

//update
// import App from './views/UpdateBarangaySelect.vue';
// import App from './views/UpdateClientSelect.vue';
// import App from './views/UpdateTown.vue';
// import App from './views/UpdateTransaction.vue';
// import App from './views/UpdateTown.vue';
// import App from './views/UpdateTownSelf.vue';
// import App from './views/UpdateSuperAdmin.vue';
// import App from './views/UpdateEstablishment.vue';
// import App from './views/UpdateBarangay.vue';
// import App from './views/UpdateClient.vue';
// import App from './views/UpdateProduct.vue';
// import App from './views/UpdateProductTransaction.vue';

import api from './views/axios.config.js'; // Import your configured Axios instance

const app = createApp(App);

// Make the api instance globally available
app.config.globalProperties.$api = api; 

// Mount the app
app.mount('#app');
