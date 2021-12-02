import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

export default new Vuex.Store({
    plugins: [createPersistedState({
        key: 'samplestorekey',
        paths: ['token.authenticationToken', 'account.authorizedAccount'],
        storage: window.sessionStorage,
    })],
});
