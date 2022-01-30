import App from './App.vue';

import router from './router';

import { createApp } from 'vue';

const app = createApp(App)
    .use(router);

router.isReady().then(() => {
    app.mount('#app');
});
