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
    }
}
</script>
