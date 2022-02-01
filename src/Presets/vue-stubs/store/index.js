import { createStore } from 'vuex';

import * as module from '@/store/modules/Module';

import createPersistedState from 'vuex-persistedstate';

export default createStore({
    modules: {
        module,
    },

    plugins: [
        createPersistedState({
            storage: window.localStorage,
            paths: [],
        }),
    ],
});
