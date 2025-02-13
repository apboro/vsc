<template>
    <PopUp :title="titleProxy"
           :message="message"
           :buttons="popUpButtons"
           :align="align"
           :close-on-overlay="closeOnOverlay"
           :manual="true"
           :resolving="resolved"
           :scrollable="scrollable"
           ref="popup"
    >
        <slot/>
    </PopUp>
</template>

<script>
import PopUp from "@/Components/PopUp";
import clone from "@/Core/Helpers/Clone";

export default {
    components: {PopUp},

    props: {
        title: {type: String, default: null},
        message: {type: String, default: null},
        buttons: {type: Array, default: () => ([{result: 'cancel', caption: 'Отмена'}])},
        saveButton: {type: String, default: 'save'},
        saveButtonCaption: {type: String, default: 'Применить'},
        align: {type: String, default: 'left'},
        manual: {type: Boolean, default: false},
        resolving: {type: Function, default: null},
        closeOnOverlay: {type: Boolean, default: false},
        form: {type: Object, default: null},
        options: {type: Object, default: () => ({})},
        scrollable: {type: Boolean, default: false},
    },

    computed: {
        popUpButtons() {
            return [{result: this.saveButton, caption: this.saveButtonCaption, color: 'blue'}, ...this.buttons];
        },
        titleProxy() {
            return this._title !== null ? this._title : this.title;
        }
    },

    data: () => ({
        resolve_function: null,
        opts: null,
        _title: null,
    }),

    methods: {
        show(options = null, title = null) {
            this.opts = options;
            this._title = title;
            this.$refs.popup.show();
            return new Promise(resolve => {
                this.resolve_function = resolve;
            });
        },

        hide() {
            return this.$refs.popup.hide();
        },

        resolved(result) {
            if (result !== this.saveButton) {
                this.$refs.popup.hide();
                return true;
            }
            if (!this.form.validate()) {
                return false;
            }
            this.$refs.popup.process(true);
            let options = clone(this.options);
            if (this.opts !== null) {
                Object.keys(this.opts).map(key => {
                    options[key] = this.opts[key];
                });
            }
            this.form.save(options)
                .then((payload) => {
                    this.$refs.popup.hide();
                    this.resolve_function(payload)
                    return true;
                })
                .finally(() => {
                    this.$refs.popup.process(false);
                });
            return false;
        },

        process(value) {
            this.$refs.popup.process(value);
        },
    }
}
</script>
