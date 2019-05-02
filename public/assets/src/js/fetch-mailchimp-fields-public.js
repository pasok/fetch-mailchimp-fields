import Vue from 'vue';

import _ from 'lodash';
Vue.set(Vue.prototype, '_', _);

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

import MailchimpSearch from './components/MailchimpSearch.vue';

if ( document.getElementById( 'fetch-mailchimp-fields-app' ) ) {
    const FetchMailchimpFieldsApp = new Vue({
        el: '#fetch-mailchimp-fields-app',
        components: {
            MailchimpSearch,
        }
    });
};
