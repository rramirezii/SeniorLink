import './bootstrap';

import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';

const app = createApp({
    components: {
        'example-component': ExampleComponent,
    }
});

app.mount("#app");