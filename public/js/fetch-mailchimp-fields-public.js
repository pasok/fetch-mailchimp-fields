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

            <span class="field" v-else-if="(apiResponse !== null) && apiResponse.hasOwnProperty(searchFieldName)">
                <span class="detail" v-if="searchFieldName === 'MERGE7'">
                    Your Bedner's Bucks balance is: <span class="value">{{ apiResponse[searchFieldName] }}</span>
                </span>
                <span v-else class="detail">
                    {{ searchFieldName }} : {{ apiResponse[searchFieldName] }}
                </span>
            </span>

            <span v-else></span>
        </div>
    </div>`;

    var vm = new Vue({
        el: '#fetch-mailchimp-fields-app',
        template: template,
        data: {
            action: 'fetch_mailchimp_fields',
            email: 'topgun@sent.com',
            apiResponse: null,
            isLoading: false,
            searchFieldName: null,
        },
        beforeMount: function () {
            this.searchFieldName = this.$el.attributes['data-field-name'].value;
        },
        methods: {
            fetchMailchimpField: function () {
                this.isLoading   = true;
                this.apiResponse = null;

                const headers    = {'Content-Type': 'application/x-www-form-urlencoded'};
                const params     = new URLSearchParams();
                params.append('action', this.action);
                params.append('email', this.email);
                params.append('field_name', this.searchFieldName);

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
