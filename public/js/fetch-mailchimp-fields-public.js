( function() {
    'use strict';

    var template = `<div id="mailchimp-fields" class="mailchimp-fields">
        <form method="post" @submit.prevent="fetchMailchimpFields">
            <input type="hidden" name="action" v-model="action">
            <div class="input-group">
                <input type="text" class="input-group-field" name="email" v-model="email" placeholder="Enter Subscriber Email">
                <button :disabled="isLoading === true" class="input-group-btn btn-sm btn-blue-dark unset-line-height" @click="fetchMailchimpFields">
                    <span v-if="isLoading === false" class="text">Get Details</span>
                    <span v-else>
                        <span class="text">Getting Details</span>
                        <span class="spinner spinner-blue"></span>
                    </span>
                </button>
            </div>
        </form>

        <div class="result text-xs my-1">
            <div v-if="apiResponse !== null">
                <span v-if="(apiResponse.hasOwnProperty('error'))" class="text-red" >{{ apiResponse.error }}</span>

                <table v-else class="table table-sm table-hover table-bordered">
                <tbody>
                    <tr v-for="(value, key) in apiResponse">
                        <td class="whitespace-no-wrap">{{ key }}</td>
                        <td>
                            <pre v-if="isJson(value)" class="bg-transparent border-transparent whitespace-normal font-mono text-xs p-0">
                                <code class="whitespace-pre-wrap">{{ prettify(value) }}</code>
                            </pre>
                            <span v-else class="font-mono text-xs">{{ value }}</span>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
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
            fieldNamesList: null,
        },
        beforeMount: function () {
            this.fieldNamesList = this.$el.attributes['data-field-names'].value;
        },
        methods: {
            fetchMailchimpFields: function () {
                const headers = {'Content-Type': 'application/x-www-form-urlencoded'};
                const params  = new URLSearchParams();
                params.append('email', this.email);
                params.append('action', this.action);
                params.append('field_names', this.fieldNamesList);

                this.isLoading   = true;
                this.apiResponse = null;
                fetch(window.ajaxurl, { method: 'POST', body: params, headers: headers })
                    .then(response => { return response.json() })
                    .then(jsonResponse => {
                        setTimeout(() => {
                            this.apiResponse = jsonResponse;
                            this.isLoading = false;
                        }, 100);
                    }).catch((error) => {
                        setTimeout(() => {
                            this.apiResponse = null;
                            this.isLoading = false;
                            console.log(error);
                        }, 100);
                    });
                    //TODO:: catch/show error
            },
            isJson: function(item) {
                if (typeof item === "object" && item !== null) {
                    return true;
                }
                try {
                    JSON.parse(item);
                    return true;
                } catch (e) {
                    return false;
                }
            },
            prettify: function(input) {
                return JSON.stringify(input, undefined, 2);
            }
        },
    });
})();
