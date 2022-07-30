window._ = require('lodash');

import Promise from "promise-polyfill";

window.Promise = Promise;

import ObjectAssign from "es6-object-assign";

ObjectAssign.polyfill();

if (typeof window.organization === "undefined") {
    window.organization = null;
}

let stateKey, eventKey;
const keys = {hidden: "visibilitychange", webkitHidden: "webkitvisibilitychange", mozHidden: "mozvisibilitychange", msHidden: "msvisibilitychange"};
for (stateKey in keys) {
    if (stateKey in document) {
        eventKey = keys[stateKey];
        break;
    }
}
document.addEventListener(eventKey, () => {
    document.cookie = 'vsc_organization=' + window.organization + ';path=/';
});

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add a response interceptor
window.axios.interceptors.request.use(
    request => {
        request.headers['X-Vcs-Organization'] = window.organization;
        return request;
    },
    error => {
        return error;
    }
);
window.axios.interceptors.response.use(
    response => {
        return response;
    },
    (error) => {
        if (error.response.status === 419 && error.response.config && !error.response.config['__isRetryRequest']) {
            return new Promise((resolve) => {
                axios.get('/sanctum/csrf-cookie')
                    .then(() => {
                        error.response.config['__isRetryRequest'] = true;
                        resolve(axios(error.response.config));
                        console.log('New token retrieved.');
                    })
                    .catch((err) => {
                        console.log('Can not retrieve new token', err);
                    });
            });
        } else if (error.response.status === 401) {
            window.location.reload();
        }

        return Promise.reject(error);
    },
);

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
