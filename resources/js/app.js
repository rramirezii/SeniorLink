import './bootstrap';

import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';
import SuperAdminDashboard from './components/SuperAdminDashboard.vue';

const app = createApp({
    components: {
        'example-component': ExampleComponent,
        'super-admin-dashboard': SuperAdminDashboard,
    }
});

app.mount("#app");
app.mount("#superdashboard");