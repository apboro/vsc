const permissionsUrl = '/api/staff/permissions/current';
const initialPermissions = typeof window.permissions !== "undefined" ? JSON.parse(window.permissions) : [];

export default {
    namespaced: true,

    state: () => ({
        permissions: initialPermissions,
    }),

    mutations: {
        setPermissions(state, payload) {
            state.permissions = payload;
        },
    },

    getters: {
        permissions(state) {
            return state.permissions;
        },
        can: (state) => (permission) => {
            return state.permissions.indexOf(permission) !== -1;
        },
    },

    actions: {
        async refresh({commit}) {

            return new Promise(function (resolve, reject) {
                axios.post(permissionsUrl, {})
                    .then(response => {
                        commit('setPermissions', response.data['data']);
                        resolve();
                    })
                    .catch(() => {
                        reject();
                    });
            });
        },
    },
};
