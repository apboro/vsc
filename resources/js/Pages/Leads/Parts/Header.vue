<template>

</template>

<script>
import data from "@/Core/Data";
import ShowcasePopUp from "@/Pages/Showcase/Components/ShowcasePopUp";
import ShowcaseGallery from "@/Pages/Showcase/Components/ShowcaseGallery";
import ShowcaseIconPlace from "@/Pages/Showcase/Icons/ShowcaseIconPlace";
import ShowcaseIconClock from "@/Pages/Showcase/Icons/ShowcaseIconClock";
import ShowcaseIconPhone from "@/Pages/Showcase/Icons/ShowcaseIconPhone";

export default {
    props: {
        crm_url: {type: String, default: 'https://lk.excurr.ru'},
        debug: {type: Boolean, default: false},
        session: {type: String, default: null},
    },

    components: {
        ShowcaseIconPhone,
        ShowcaseIconClock,
        ShowcaseIconPlace,
        ShowcaseGallery,
        ShowcasePopUp,
    },

    computed: {
        title() {
            return 'Причал — ' + this.info.data['name'];
        },
        work_time() {
            return typeof this.info.data['work_time'] !== "undefined" && this.info.data['work_time'] !== null ? this.info.data['work_time'].split('\n') : [];
        },
        way_to() {
            return typeof this.info.data['way_to'] !== "undefined" && this.info.data['way_to'] !== null ? this.info.data['way_to'].split('\n') : [];
        },
    },

    data: () => ({
        info: data('/showcase/pier'),
    }),

    methods: {
        show(id) {
            this.info.url = this.url('/showcase/pier');
            this.info.reset();
            this.$refs.popup.show();
            this.$refs.popup.process(true);
            this.info.load({id: id}, {'X-Ap-External-Session': this.session})
                .finally(() => {
                    this.$refs.popup.process(false);
                })
        },
        url(path) {
            return this.crm_url + path + (this.debug ? '?XDEBUG_SESSION_START=PHPSTORM' : '');
        },
    }
}
</script>

<style lang="scss" scoped>
@import "../variables";
</style>
