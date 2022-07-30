const permissionsUrl = '/api/staff/permissions/current';
const initialPermissions = typeof window.permissions !== "undefined" ? JSON.parse(window.permissions) : [];
const initialRoles = typeof window.roles !== "undefined" ? JSON.parse(window.roles) : [];

export default {
    namespaced: true,

    state: () => ({
        permissions: initialPermissions,
        roles: initialRoles,
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
        roles(state) {
            return state.roles;
        },
        hasRole: (state) => (role) => {
            return state.roles.indexOf(role) !== -1;
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
