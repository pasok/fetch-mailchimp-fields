( function() {
    'use strict';

        var template = `<div class="mailchimp_fields">
            <h6>Fetch Mailchimp Field</h6>
            <form class="mailchimp_fields_form" method="post" @submit.prevent="fetchMailchimpField()">
                <input type="text" name="email" v-model="email" size="40">
                <input type="hidden" name="action" v-model="action">
                <button>
                    <span v-show="isLoading === false">Get Details</span>
                    <span v-show="isLoading === true">Getting Details</span>
                </button>
            </form>
            <div class="result">
                <span class="text-error" v-if="(apiResponse !== null) && apiResponse.hasOwnProperty('error')">
                    {{ apiResponse.error }}
                </span>
                <span v-else>{{ apiResponse }}</span>
            </div>
        </div>`;

        var vm = new Vue({
            el: '#fetch-mailchimp-fields-app',
            template: template,
            data: {
                action: 'fetch_mailchimp_fields',
                email: 'topgun_1@sent.com',
                apiResponse: null,
                isLoading: false,
            },
            methods: {
                fetchMailchimpField: function () {
                    this.isLoading = true;
                    this.apiResponse = null;

                    var formData  = new FormData();
                    formData.append('action', this.action);
                    formData.append('email', this.email);

                    fetch(window.ajaxurl, { method: 'POST', body: formData })
                        .then( function(response) { return response.json() })
                        .then( function(json) {
                            console.log(json);
                            this.apiResponse = json;
                            this.isLoading = false;
                        }.bind(this) );
                }
            },
        });


})();
