
import * as views from '@/view';

const routes = [
    { path: '/view-town', component: views.ViewTown, name: 'ViewTown' },
    // { path: '/view-client-per-barangay', component: views.ViewClientPerBarangay, name: 'ViewClientPerBarangay' },
    { path: '/view-barangay-per-town', component: views.ViewBarangayPerTown, name: 'ViewBarangayPerTown' },
    { path: '/view-client-list', component: views.ViewClientList, name: 'ViewClientList' },
    { path: '/view-barangay', component: views.ViewBarangay, name: 'ViewBarangay' },
    { path: '/view-super-admin', component: views.ViewSuperAdmin, name: 'ViewSuperAdmin' },
    { path: '/view-establishment', component: views.ViewEstablishment, name: 'ViewEstablishment' },
    { path: '/view-establishment-view-button', component: views.ViewEstablishmentViewButton, name: 'ViewEstablishmentViewButton' },
    { path: '/view-barangay-w-delete', component: views.ViewBarangayWDelete, name: 'ViewBarangayWDelete' },
    { path: '/view-products-transaction', component: views.ViewProductsTransaction, name: 'ViewProductsTransaction' },
    { path: '/super-admin-dashboard', component: views.SuperAdminDashboard, name: 'SuperAdminDashboard' },
    { path: '/client-dashboard-phone', component: views.ClientDashboardPhone, name: 'ClientDashboardPhone' },
    { path: '/client-main', component: views.ClientMain, name: 'ClientMain' },
    { path: '/client-dashboard', component: views.ClientDashboard, name: 'ClientDashboard' },
    { path: '/barangay-dashboard', component: views.BarangayDashboard, name: 'BarangayDashboard' },
    { path: '/establishment-dashboard', component: views.EstablishmentDashboard, name: 'EstablishmentDashboard' },
    { path: '/town-dashboard', component: views.TownDashboard, name: 'TownDashboard' },
    { path: '/client-select-menu', component: views.ClientSelectMenu, name: 'ClientSelectMenu' },
    { path: '/', component: views.ClientLogin, name: 'ClientLogin' },
    { path: '/client-auth', component: views.ClientAuth, name: 'ClientAuth' },
    { path: '/client-qr', component: views.ClientQR, name: 'ClientQR' },
    { path: '/client-profile', component: views.ClientProfile, name: 'ClientProfile' },
    { path: '/update-barangay-select', component: views.UpdateBarangaySelect, name: 'UpdateBarangaySelect' },
    { path: '/update-client-select', component: views.UpdateClientSelect, name: 'UpdateClientSelect' },
    { path: '/update-town', component: views.UpdateTown, name: 'UpdateTown' },
    { path: '/update-transaction', component: views.UpdateTransaction, name: 'UpdateTransaction' },
    { path: '/update-town-self', component: views.UpdateTownSelf, name: 'UpdateTownSelf' },
    { path: '/update-super-admin', component: views.UpdateSuperAdmin, name: 'UpdateSuperAdmin' },
    { path: '/update-establishment', component: views.UpdateEstablishment, name: 'UpdateEstablishment' },
    { path: '/update-barangay', component: views.UpdateBarangay, name: 'UpdateBarangay' },
    { path: '/update-client', component: views.UpdateClient, name: 'UpdateClient' },
    // { path: '/update-product', component: views.UpdateProduct, name: 'UpdateProduct' },
    // { path: '/update-product-transaction', component: views.UpdateProductTransaction, name: 'UpdateProductTransaction' },
    { path: '/create-town', component: views.CreateTown, name: 'CreateTown' },
    { path: '/create-establishment', component: views.CreateEstablishment, name: 'CreateEstablishment' },
    { path: '/create-barangay', component: views.CreateBarangay, name: 'CreateBarangay' },
    { path: '/create-client', component: views.CreateClient, name: 'CreateClient' }
  ];
  
  export default routes;
  