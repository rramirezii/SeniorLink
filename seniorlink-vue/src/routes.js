import * as views from '@/view';

const routes = [
    // GENERAL
    { path: '/', component: views.ClientLogin, name: 'ClientLogin' },
    { path: '/authentication/senior/:username', component: views.ClientAuth, name: 'ClientAuth', props:true },
    { path: '/authentication/admin/:username', component: views.LoginPassword, name: 'LoginPassword', props: true },
  
    // super admin
    { path: '/admin/dashboard', component: views.SuperAdminDashboard, name: 'SuperAdminDashboard' },
    { path: '/admin/', redirect: '/admin/dashboard'},

    { path: '/admin/create/town', component: views.CreateTown, name: 'CreateTown' },
    { path: '/admin/create/establishment', component: views.CreateEstablishment, name: 'CreateEstablishment' },
    { path: '/admin/create/superadmin', component: views.CreateSuperAdmin, name: 'CreateSuperAdmin' },

    { path: '/admin/view/towns', component: views.ViewTown, name: 'ViewTown' }, 
    { path: '/admin/view/seniors', component: views.ViewClientList, name: 'ViewClientListBarangay' },
    { path: '/admin/view/barangays', component: views.ViewBarangay, name: 'ViewBarangay' },
    { path: '/admin/view/town/barangay', component: views.ViewBarangayPerTown, name: 'ViewBarangayPerTown' }, //not working
    { path: '/admin/view/admins', component: views.ViewSuperAdmin, name: 'ViewSuperAdminUpdateDelete' },
    { path: '/admin/view/establishments', component: views.ViewEstablishmentUpdateDelete, name: 'ViewEstablishmentUpdateDelete' },
    { path: '/admin/view/barangays', component: views.ViewBarangayUpdateDelete, name: 'ViewBarangayUpdateDelete' },
    { path: '/admin/view/town/barangay', component: views.ViewPerTown, name: 'ViewPerTown' },
    { path: '/admin/view/super-admins', component: views.ViewSuperAdmin, name: 'ViewSuperAdmin' },

    { path: '/admin/update/town/:username', component: views.UpdateTown, name: 'UpdateTown',  props: true},
    { path: '/admin/update/admin/:username', component: views.UpdateSuperAdmin, name: 'UpdateSuperAdmin', props: true },
    { path: '/admin/update/establishment/:username', component: views.UpdateEstablishment, name: 'UpdateEstablishment', props: true },

    // deletes are enforcedd in view

    // TOWN
    { path: '/town/dashboard', component: views.TownDashboard, name: 'TownDashboard' },
    { path: '/town/', redirect: '/town/dashboard'},

    { path: '/town/create/barangay', component: views.CreateBarangay, name: 'CreateBarangay' },
    

    { path: '/town/view/barangays', component: views.ViewBarangayUpdateDelete, name: 'ViewBarangayUpdateDelete' },
    // { path: '/view-client-per-barangay', component: views.ViewClientPerBarangay, name: 'ViewClientPerBarangay' },

    { path: '/view-client-per-barangay', component: views.ViewPerBarangay, name: 'ViewPerBarangay' }, // view transactions of senior per barngay
    { path: '/town/view/seniors', component: views.ViewClientList, name: 'ViewClientList' },
    // view transactions of senior per barngay

    { path: '/town/update/barangay/:username', component: views.UpdateBarangay, name: 'UpdateBarangay', props: true },
    // update town self
    { path: '/update-town-self', component: views.UpdateTownSelf, name: 'UpdateTownSelf' },  // update town self


    // BARANGAY
    { path: '/barangay/dashboard', component: views.BarangayDashboard, name: 'BarangayDashboard' },
    { path: '/barangay/', redirect: '/barangay/dashboard'},
    { path: '/barangay/create/senior', component: views.CreateClient, name: 'CreateClient' },

    { path: '/barangay/view/seniors', component: views.ViewClientListUpdateDelete, name: 'ViewClientListUpdateDelete' },
    // read senior transaction

    { path: '/barangay/update/senior/:username', component: views.UpdateClient, name: 'UpdateClient', props: true },
    { path: '/barangay/update/self', component: views.UpdateBarangaySelect, name: 'UpdateBarangaySelect' },
    { path: '/barangay/update/self', component: views.UpdateBarangaySelf, name: 'UpdateBarangaySelf' },

    // Establishment
    { path: '/establishment/dashboard', component: views.EstablishmentDashboard, name: 'EstablishmentDashboard' },
    { path: '/establishment/', redirect: '/establishment/dashboard'},
    { path: '/establishment/transaction', component: views.EstablishmentTransaction, name: 'EstablishmentTransaction' },

    // Read senior
    // read senior transation

    { path: '/establishment/view/transaction/:senior_username', component: views.ViewTransaction, name: 'ViewTransaction', props:true },
    { path: '/establishment/create/transaction/:senior_username', component: views.CreateTransaction, name: 'CreateTransaction', props: true },
    { path: '/establishment/update/transaction', component: views.UpdateTransaction, name: 'UpdateTransaction' },
    // update establishment password
    { path: '/establishment/update-account', component: views.UpdateEstablishmentSelf, name: 'UpdateEstablishmentSelf' }, // update establishment password
    { path: '/establishment/create-product', component: views.CreateProduct, name: 'CreateProduct' },
    { path: '/establishment/update-product', component: views.UpdateProduct, name: 'UpdateProduct' },
    { path: '/establishment/update/transaction/:senior_username/:transaction_id', component: views.UpdateTransactionIndividual, name: 'UpdateTransactionIndividual', props: true },


    // Senior
    { path: '/senior/dashboard', component: views.ClientDashboard, name: 'ClientDashboard' }, // WHICH IS THE DASHBOARD
    { path: '/senior/', redirect: '/senior/dashboard'},

    { path: '/senior/profile/:username', component: views.ClientProfile, name: 'ClientProfile', props:true }, // WHICH IS THE DASHBOARD
    { path: '/senior/qr/:username', component: views.ClientQR, name: 'ClientQR', props:true },
    { path: '/senior/transaction/:username', component: views.ClientTransactions, name: 'ClientTransactions', props:true},

    { path: '/senior/dashboard/phone', component: views.ClientDashboardPhone, name: 'ClientDashboardPhone' },
    { path: '/senior/dashboard/menu', component: views.ClientSelectMenu, name: 'ClientSelectMenu' }, //WHICH IS THE DASHBOARD
    

    { path: '/senior/update/self', component: views.UpdateClientSelect, name: 'UpdateClientSelect' },
    // { path: '/senior/transactions', component: views.ClientMain, name: 'ClientMain' }, # waht is this?
    { path: '/senior/update/self', component: views.UpdateClientSelf, name: 'UpdateClientSelf' },
    { path: '/senior/transactions-print', component: views.PrintTransactions, name: 'PrintTransactions' },


    // { path: '/view-products-transaction', component: views.ViewProductsTransaction, name: 'ViewProductsTransaction' },
  //  { path: '/update-town-self', component: views.UpdateTownSelf, name: 'UpdateTownSelf' },
   // { path: '/update-product', component: views.UpdateProduct, name: 'UpdateProduct' },
    // { path: '/update-product-transaction', component: views.UpdateProductTransaction, name: 'UpdateProductTransaction' },

  ];

  export default routes;
