<script>
export default {
    methods: {
        can(permission) {
            if (typeof permission === "string") {
                return this.$store.getters['permissions/can'](permission);
            }
            if (typeof permission === "object" && permission !== null) {
                return Object.keys(permission).some(key => this.$store.getters['permissions/can'](permission[key]));
            }
        },
        hasRole(role) {
            if (typeof role === "string") {
                return this.$store.getters['permissions/hasRole'](role);
            }
            if (typeof role === "object" && role !== null) {
                return Object.keys(role).some(key => this.$store.getters['permissions/hasRole'](role[key]));
            }
        },
    }
}
</script>
