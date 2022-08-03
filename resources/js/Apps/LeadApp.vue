<template>
    <GuiContainer>
        <NewLead
            :session="session"
            :crm_url="crm_url"
            :debug="debug"
            :services="services"
        />
    </GuiContainer>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import NewLead from "@/Pages/Leads/NewLead";

export default {
    components: {NewLead, GuiContainer},
    props: {
        crm_url: {type: String, default: null},
        debug: {type: Boolean, default: false},
    },

    data: () => ({
        key: null,
        session: null,
        services: [],
    }),

    created() {
        const configElement = document.getElementById('vsc-lead-config');
        const config = configElement !== null ? JSON.parse(configElement.innerHTML) : null;
        if (config) {
            this.key = config['key'];
        }

        axios.post(this.url('/leads/init'), {}, {headers: {'X-Vsc-Key': this.key}})
            .then(response => {
                this.session = response.data.data['session'];
                this.services = response.data.data['services'];
            });
    },

    methods: {
        /**
         * Helper function for url making.
         *
         * @param path
         * @returns {string}
         */
        url(path) {
            return this.crm_url + path + (this.debug ? '?XDEBUG_SESSION_START=PHPSTORM' : '');
        },
    },
}
</script>
