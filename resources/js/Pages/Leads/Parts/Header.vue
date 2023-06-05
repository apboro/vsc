<template>
    <div class="header">
        <div class="header__first-container">
            <div class="header__title">
                <div class="header__title_first">
                    Записаться
                </div>

                <div class="header__title_second">
                    на регулярные тренировочные занятия
                </div>
            </div>
        </div>

        <div class="header__second-container">
            <img class="logo" src="./../assets/logo.png" width="80" alt="">
        </div>
    </div>
</template>

<script>
import data from "@/Core/Data";

export default {
    props: {
        crm_url: {type: String, default: 'https://lk.excurr.ru'},
        debug: {type: Boolean, default: false},
        session: {type: String, default: null},
    },

    components: {
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
@import "../../../../css/fonts";

.header {
    display: flex;
    width: 100%;
    align-items: flex-end;

    &__first-container {
        background-color: #f6ac2e;
        width: 54%;
        display: flex;
        align-items: flex-end;
        height: 70px;
    }

    &__second-container {
        display: flex;
        justify-content: flex-end;
        width: 46%;
    }

    &__title {
        padding-left: 20px;

        &_first {
            font-family: $heading_now_font;
            text-transform: uppercase;
            font-size: 72px;
            line-height: 1;
        }

        &_second {
            font-family: $heading_now_font;
            text-transform: uppercase;
            font-size: 38px;
            line-height: 1;
        }
    }
}

.logo {
    padding-right: 15px;
}

</style>
