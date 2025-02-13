<template>
    <GuiContainer>
        <LoadingProgress :loading="is_initializing">
            <NewLeadSingle v-if="!is_initializing && subscription_id === null && message === null"
                     :session="session"
                     :crm_url="crmUrl"
                     :debug="debug"
                     :services="services"
                     :regions="regions"
            />
            <SingleContractForm v-if="!is_initializing && subscription_id !== null && message === null"
                          :session="session"
                          :crm_url="crmUrl"
                          :debug="debug"
                          :subscription-key="subscriptionKey"
                          :subscription-id="subscription_id"
                          :subscription-data="subscription_data"
                          :service-data="service_data"
                          :discounts="discounts"
            />
            <GuiMessage v-if="!is_initializing && message !== null">
                {{ message }}
            </GuiMessage>
            <GuiMessage v-if="is_initializing">
                Загрузка...
            </GuiMessage>
        </LoadingProgress>
    </GuiContainer>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import NewLeadSingle from "@/Pages/Leads/NewLeadSingle";
import LoadingProgress from "@/Components/LoadingProgress";
import SingleContractForm from "@/Pages/Leads/SingleContractForm";
import GuiMessage from "@/Components/GUI/GuiMessage";

export default {
    components: {GuiMessage, SingleContractForm, LoadingProgress, NewLeadSingle, GuiContainer},
    props: {
        crm_url: {type: String, default: null},
        debug: {type: Boolean, default: false},
    },

    computed: {
        crmUrl() {
            return this.crm_url_override ? this.crm_url_override : this.crm_url;
        },
    },

    data: () => ({
        key: null,
        session: null,
        services: [],
        regions: [],
        subscriptionKey: null,
        subscription_id: null,
        subscription_data: null,
        service_data: null,
        discounts: null,

        is_initializing: true,
        message: null,
        crm_url_override: null,
    }),

    created() {
        const configElement = document.getElementById('vsc-lead-config');
        const config = configElement !== null ? JSON.parse(configElement.innerHTML) : null;
        if (config) {
            this.key = config['key'];
            this.crm_url_override = config['crm_url_override'];
        }

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('sbsc')) {
            this.subscriptionKey = urlParams.get('sbsc');
        }

        axios.post(this.url('/leads-single/init'), {subscription_key: this.subscriptionKey}, {headers: {'X-Vsc-Key': this.key}})
            .then(response => {
                this.session = response.data.data['session'];
                this.services = response.data.data['services'];
                this.regions = response.data.data['regions'];
                this.subscription_id = response.data.data['subscription_id'];
                this.subscription_data = response.data.data['subscription_data'];
                this.service_data = response.data.data['service_data'];
                this.discounts = response.data.data['discounts'];
            })
            .catch(error => {
                this.message = error.response.data['message'];
            })
            .finally(() => {
                this.is_initializing = false;
            })
    },

    methods: {
        /**
         * Helper function for url making.
         *
         * @param path
         * @returns {string}
         */
        url(path) {
            return this.crmUrl + path + (this.debug ? '?XDEBUG_SESSION_START=PHPSTORM' : '');
        },
    },
}
</script>
