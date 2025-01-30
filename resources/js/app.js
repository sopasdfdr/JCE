import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// Import Vuetify and its styles
import { createVuetify } from 'vuetify';
import 'vuetify/styles'; // Vuetify core styles
import '@mdi/font/css/materialdesignicons.css'; // MDI icons

// Create Vuetify instance
const vuetify = createVuetify({
  icons: {
    defaultSet: 'mdi', // Use MDI as the default icon set
  },
});

// Create Vue app
createApp(App)
  .use(router) // Use Vue Router
  .use(vuetify) // Use Vuetify
  .mount('#app'); // Mount the app to the DOM
