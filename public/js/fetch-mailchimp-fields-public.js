( function() {
    'use strict';

        var template = `<div class="mailchimp_fields">
            <h6>Fetch Mailchimp Field</h6>
            <form class="mailchimp_fields_form" method="post" @submit.prevent="fetchMailchimpField()">
                <input type="text" name="email" v-model="email" size="60">
                <input type="hidden" name="action" v-model="action">
                <a class="button" style="position:relative" @click="fetchMailchimpField">
                    <span v-show="isLoading === false">Get Details</span>
                    <span v-show="isLoading === true"><span class="spinner">Getting Details</span> </span>
                </a>
            </form>
            <div class="result">
                <span class="text-error" v-if="(apiResponse !== null) && apiResponse.hasOwnProperty('error')">
                    {{ apiResponse.error }}
                </span>
                <table class="field-list" v-else-if="(apiResponse !== null)">
                    <tr>
                        <th class="merge-field">Field</th>
                        <th class="merge-value">Value</th>
                    </tr>
                    <tr v-for="(value, key) in apiResponse">
                        <td>{{ key }}</td>
                        <td>{{ value }}</td>
                    </tr>
                </table>
                <span v-else></span>
            </div>
        </div>`;

        var vm = new Vue({
            el: '#fetch-mailchimp-fields-app',
            template: template,
            data: {
                action: 'fetch_mailchimp_fields',
                email: '',
                apiResponse: null,
                isLoading: false,
            },
            methods: {
                fetchMailchimpField: function () {
                    this.isLoading   = true;
                    this.apiResponse = null;

                    const headers    = {'Content-Type': 'application/x-www-form-urlencoded'};
                    const params     = new URLSearchParams();
                    params.append('action', this.action);
                    params.append('email', this.email);

                    fetch(window.ajaxurl, { method: 'POST', body: params, headers: headers })
                        .then(response => { return response.json() })
                        .then(jsonResponse => {
                            setTimeout(() => {
                                this.apiResponse = jsonResponse;
                                this.isLoading = false;
                            }, 300);
                        });
                }
            },
        });


})();
