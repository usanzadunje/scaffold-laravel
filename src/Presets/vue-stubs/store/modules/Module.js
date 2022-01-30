export const namespaced = true;

export const state = {
    test: 'Testing store value...',
};

export const mutations = {
    SET_TEST(state, payload) {
        state.test = payload;
    },
};

export const actions = {};

export const getters = {
    test: (state) => {
        return state.test;
    },
};