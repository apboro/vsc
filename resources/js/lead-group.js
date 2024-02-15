import LeadAppGroup from "@/Apps/LeadAppGroup.vue";

const origin = window.location.protocol + "//" + window.location.host;
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Access-Control-Allow-Origin'] = origin;
window.axios.defaults.headers.common['Access-Control-Allow-Methods'] = 'POST';
window.axios.defaults.withCredentials = true;

import {createApp} from 'vue';


const config = require('config');

const leadApp = createApp(LeadAppGroup, {crm_url: config['crm_url'], debug: config['debug']});

document.addEventListener('DOMContentLoaded', () => {
    leadApp.mount('#vsc-lead-group');
})
