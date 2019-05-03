<template>
<div class="mailchimp-fields">
    <form method="post" @submit.prevent="submitForm">
        <div class="input-group">
            <input type="text" class="input-group-field" name="email" placeholder="Enter Subscriber Email"
                v-model="email" v-validate="'required|email'" data-vv-as="Email" data-vv-validate-on="change">
            <button :disabled="isLoading === true" class="input-group-btn btn-sm btn-blue-dark unset-line-height" @click="submitForm">
                <span v-if="isLoading === false" class="text">Get Details</span>
                <span v-else>
                    <span class="text">Getting Details</span>
                    <span class="spinner spinner-blue"></span>
                </span>
            </button>
        </div>
        <span class="text-sm text-red">{{ errors.first('email') }}</span>
    </form>

    <div class="result text-sm my-1">
        <div v-if="(_.isObject(apiResponse) && !_.has(apiResponse, 'error'))">
            <table class="table table-sm table-hover table-bordered">
            <tbody>
                <tr v-for="(value, key) in apiResponse">
                    <td class="whitespace-no-wrap">{{ getDisplayName(key) }}</td>
                    <td class="seventy-five-percent">
                        <pre v-if="_.isObject(value)" class="bg-transparent border-transparent whitespace-normal font-mono text-sm p-0 m-0">
                            <code class="whitespace-pre-wrap">{{ prettifyJson(value) }}</code>
                        </pre>
                        <span v-else class="font-mono text-sm">{{ value }}</span>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>
</div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';

axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
axios.defaults.timeout = 60000; // 60 seconds

export default {
    name: 'MailchimpSearch',
    props: {
        ajaxUrl: '',
        action: '',
        nonceToken: '',
        fieldNames: '',
        fieldDisplayNames: '',
    },
    data() {
        return {
            email: '',
            apiResponse: null,
            isLoading: false,
        };
    },
    computed: {
        displayNames: function () {
            return this.parseJson(this.fieldDisplayNames);
        }
    },
    methods: {
        parseJson: function(input) {
            try { return JSON.parse(input) }
            catch(e) { return null }
        },
        prettifyJson: function(input) {
            return JSON.stringify(input, undefined, 2);
        },
        setError: function (msg = 'Failed to fetch information from server') {
            this.errors.remove('email');
            this.errors.add({ field: 'email', msg: msg });
        },
        getDisplayName: function(fieldName) {
            if (_.isObject(this.displayNames) && _.has(this.displayNames, fieldName)) {
                return this.displayNames[fieldName];
            }
            return fieldName;
        },
        emptyApiReponseBeforeAjax: function()  {
            this.apiResponse = null;
            this.isLoading = true;
        },
        updateApiReponseAfterAjax: function(serverResponse)  {
            setTimeout(() => {
                if (!_.isObject(serverResponse)) {
                    this.setError();
                }
                if (_.isObject(serverResponse) && _.has(serverResponse, 'error')) {
                    this.setError(serverResponse.error);
                }

                this.apiResponse = serverResponse;
                this.isLoading = false;
            }, 100);
        },
        async submitForm() {
            if (!await this.$validator.validate()) { return false; }

            const params = new URLSearchParams();
            params.append('nonce_token', this.nonceToken);
            params.append('action', this.action);
            params.append('email', this.email);
            params.append('field_names', this.fieldNames);

            this.emptyApiReponseBeforeAjax();
            axios.post(this.ajaxUrl, params)
                .then(response => this.updateApiReponseAfterAjax(response.data))
                .catch(error => {
                    const errorResponse = _.has(error, 'response.data') ? error.response.data : null
                    this.updateApiReponseAfterAjax(errorResponse)
                });
        },
    },
};
</script>
