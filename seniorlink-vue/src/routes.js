
import * as views from '@/view';

const routes = [
    // GENERAL
    { path: '/', component: views.ClientLogin, name: 'ClientLogin' },
    { path: '/authentication/senior', component: views.ClientAuth, name: 'ClientAuth' },
    { path: '/profile', component: views.ClientProfile, name: 'ClientProfile' },
    { path: '/authentication/admin', component: views.LoginPassword, name: 'LoginPassword' },
  

    // super admin
    { path: '/admin/dashboard', component: views.SuperAdminDashboard, name: 'SuperAdminDashboard' },
    
    { path: '/admin/create/town', component: views.CreateTown, name: 'CreateTown' },
    { path: '/admin/create/establishment', component: views.CreateEstablishment, name: 'CreateEstablishment' },
    //craete super admin

    { path: '/admin/view/towns', component: views.ViewTown, name: 'ViewTown' }, //add delete vutton
    { path: '/admin/view/seniors', component: views.ViewClientList, name: 'ViewClientList' },
    { path: '/admin/view/barangays', component: views.ViewBarangay, name: 'ViewBarangay' },
    { path: '/admin/view/town/barangay', component: views.ViewBarangayPerTown, name: 'ViewBarangayPerTown' },
    { path: '/admin/view/super-admins', component: views.ViewSuperAdmin, name: 'ViewSuperAdmin' }, //add deelete button
    { path: '/admin/view/establishments', component: views.ViewEstablishmentUpdateDelete, name: 'ViewEstablishmentUpdateDelete' },
    
    { path: '/admin/update/town', component: views.UpdateTown, name: 'UpdateTown' },
    { path: '/admin/update/super-admin', component: views.UpdateSuperAdmin, name: 'UpdateSuperAdmin' },
    { path: '/admin/update/establishment', component: views.UpdateEstablishment, name: 'UpdateEstablishment' },
    
    // deletes are enforcedd in view
    // TOWN
    { path: '/town/dashboard', component: views.TownDashboard, name: 'TownDashboard' },
    
    { path: '/town/create/barangay', component: views.CreateBarangay, name: 'CreateBarangay' },
    
    { path: '/town/view/barangays', component: views.ViewBarangayUpdateDelete, name: 'ViewBarangayUpdateDelete' },
    // { path: '/view-client-per-barangay', component: views.ViewClientPerBarangay, name: 'ViewClientPerBarangay' },
    { path: '/town/view/seniors', component: views.ViewClientList, name: 'ViewClientList' },
    // view transactions of senior per barngay

    { path: '/town/update/barangay', component: views.UpdateBarangay, name: 'UpdateBarangay' },
    // update town self

    // BARANGAY
    { path: '/barangay/dashboard', component: views.BarangayDashboard, name: 'BarangayDashboard' },
    
    { path: '/barangay/create/senior', component: views.CreateClient, name: 'CreateClient' },
    
    { path: '/barangay/view/seniors', component: views.ViewClientListUpdateDelete, name: 'ViewClientListUpdateDelete' },
    // read senior transaction

    { path: '/barangay/update/senior', component: views.UpdateClient, name: 'UpdateClient' },
    { path: '/barangay/update/self', component: views.UpdateBarangaySelect, name: 'UpdateBarangaySelect' },
    
    // Establishment
    { path: '/establishment/dashboard', component: views.EstablishmentDashboard, name: 'EstablishmentDashboard' },
    
    // Read senior
    // read senior transation

    { path: '/establishment/update/transaction', component: views.UpdateTransaction, name: 'UpdateTransaction' },
    // update establishment password

    // Senior
    { path: '/senior/dashboard', component: views.ClientDashboard, name: 'ClientDashboard' }, // WHICH IS THE DASHBOARD
    { path: '/senior/dashboard/phone', component: views.ClientDashboardPhone, name: 'ClientDashboardPhone' },
    { path: '/senior/dashboard/menu', component: views.ClientSelectMenu, name: 'ClientSelectMenu' }, //WHICH IS THE DASHBOARD
    { path: '/senior/qr', component: views.ClientQR, name: 'ClientQR' },

    { path: '/senior/update/self', component: views.UpdateClientSelect, name: 'UpdateClientSelect' },
    { path: '/senior/transactions', component: views.ClientMain, name: 'ClientMain' },
    

    // { path: '/view-products-transaction', component: views.ViewProductsTransaction, name: 'ViewProductsTransaction' },
  //  { path: '/update-town-self', component: views.UpdateTownSelf, name: 'UpdateTownSelf' },
   // { path: '/update-product', component: views.UpdateProduct, name: 'UpdateProduct' },
    // { path: '/update-product-transaction', component: views.UpdateProductTransaction, name: 'UpdateProductTransaction' },
  ];
  
  export default routes;
  