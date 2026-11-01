import './echo';
import { createApp } from 'vue';
import App from './components/App.vue';

/* Vue-приложение монтируется в <div id="app"> из layouts/app.blade.php. */
createApp(App).mount('#app');
