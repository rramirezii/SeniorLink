import * as views from '@/view';

const routes = [
    // GENERAL
    { path: '/', component: views.ClientLogin, name: 'ClientLogin' },
    { path: '/authentication/senior', component: views.ClientAuth, name: 'ClientAuth' },
    { path: '/profile', component: views.ClientProfile, name: 'ClientProfile' },
    { path: '/authentication/admin', component: views.LoginPassword, name: 'LoginPassword' },
  

    // super admin
    { path: '/admin/dashboard', component: views.SuperAdminDashboard, name: 'SuperAdminDashboard' },
    { path: '/admin/', redirect: '/admin/dashboard'},
    
    { path: '/admin/create/town', component: views.CreateTown, name: 'CreateTown' },
    { path: '/admin/create/establishment', component: views.CreateEstablishment, name: 'CreateEstablishment' },
    { path: '/admin/create/superadmin', component: views.CreateSuperAdmin, name: 'CreateSuperAdmin' },

    { path: '/admin/view/towns', component: views.ViewTown, name: 'ViewTown' }, //add delete vutton
    { path: '/admin/view/seniors', component: views.ViewClientList, name: 'ViewClientListBarangay' },
    { path: '/admin/view/barangays', component: views.ViewBarangay, name: 'ViewBarangay' },
    { path: '/admin/view/town/barangay', component: views.ViewPerTown, name: 'ViewPerTown' }, //not working
    { path: '/admin/view/super-admins', component: views.ViewSuperAdmin, name: 'ViewSuperAdmin' }, //add deelete button
    { path: '/admin/view/establishments', component: views.ViewEstablishmentUpdateDelete, name: 'ViewEstablishmentUpdateDelete' },
    
    { path: '/admin/update/town', component: views.UpdateTown, name: 'UpdateTown' },
    { path: '/admin/update/super-admin', component: views.UpdateSuperAdmin, name: 'UpdateSuperAdmin' },
    { path: '/admin/update/establishment', component: views.UpdateEstablishment, name: 'UpdateEstablishment' },
    
    // deletes are enforcedd in view
    // TOWN
    { path: '/town/dashboard', component: views.TownDashboard, name: 'TownDashboard' },
    { path: '/town/', redirect: '/town/dashboard'},
    
    { path: '/town/create/barangay', component: views.CreateBarangay, name: 'CreateBarangay' },

    { path: '/town/view/barangays', component: views.ViewBarangayUpdateDelete, name: 'ViewBarangayUpdateDelete' },
    { path: '/view-client-per-barangay', component: views.ViewPerBarangay, name: 'ViewPerBarangay' },
    { path: '/town/view/seniors', component: views.ViewClientList, name: 'ViewClientList' },
    // view transactions of senior per barngay

    { path: '/town/update/barangay', component: views.UpdateBarangay, name: 'UpdateBarangay' },
    // update town self

    // BARANGAY
    { path: '/barangay/dashboard', component: views.BarangayDashboard, name: 'BarangayDashboard' },
    { path: '/barangay/', redirect: '/barangay/dashboard'},

    { path: '/barangay/create/senior', component: views.CreateClient, name: 'CreateClient' },
    
    { path: '/barangay/view/seniors', component: views.ViewClientListUpdateDelete, name: 'ViewClientListUpdateDelete' },
    // read senior transaction
    { path: '/barangay/update/senior', component: views.UpdateClient, name: 'UpdateClient' },
    { path: '/barangay/update/self', component: views.UpdateBarangaySelf, name: 'UpdateBarangaySelf' },
    
    // Establishment
    { path: '/establishment/dashboard', component: views.EstablishmentDashboard, name: 'EstablishmentDashboard' },
    { path: '/establishment/', redirect: '/establishment/dashboard'},
    { path: '/establishment/transaction', component: views.EstablishmentTransaction, name: 'EstablishmentTransaction' },
    { path: '/establishment/create/transaction', component: views.CreateTransaction, name: 'CreateTransaction' },
    { path: '/view/transaction', component: views.ViewTransaction, name: 'ViewTransaction' },
    { path: 'establishment/update-product', component: views.UpdateProduct, name: 'UpdateProduct' },
    { path: 'establishment/create-product', component: views.CreateProduct, name: 'CreateProduct' },
    // Read senior
    // read senior transation

    { path: '/establishment/update/transaction', component: views.UpdateTransaction, name: 'UpdateTransaction' },
    // update establishment password

    // Senior
    { path: '/senior/', redirect: '/senior/dashboard'},
    { path: '/senior/dashboard', component: views.ClientDashboard, name: 'ClientDashboard' }, //THIS IS THE SENIOR DASHBOARD
    { path: '/senior/qr', component: views.ClientQR, name: 'ClientQR' },
    { path: '/senior/update/self', component: views.UpdateClientSelf, name: 'UpdateClientSelf' },
    { path: '/senior/transactions', component: views.ClientTransactions, name: 'ClientTransactions' },
    

    
  //  { path: '/update-town-self', component: views.UpdateTownSelf, name: 'UpdateTownSelf' },
  
  ];
  
  export default routes;
  