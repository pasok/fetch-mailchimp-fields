<?php

namespace App;

class MailChimpApi
{
    private $api_key;
    private $list_id;
    private $error;
    private $mailchimp;

    public function __construct()
    {
        $this->api_key = get_option('mailchimp_config_api_key');
        $this->list_id = get_option('mailchimp_config_list_id');

        try {
            $this->mailchimp = new \DrewM\MailChimp\MailChimp($this->api_key);
        } catch(\Exception $e) {
            $this->set_error($e->getMessage());
        }
    }

    public function get_all_merge_fields_of_list($list_id = null)
    {
        if(!empty($this->error)) { return false; }

        $list_id = !empty($list_id) ? $list_id : $this->list_id;
        try {
            $api_url = "lists/{$list_id}/merge-fields";
            $api_response = $this->mailchimp->get($api_url);
            if (array_key_exists('merge_fields', $api_response) && !empty($api_response['merge_fields'])) {
                return json_encode(array_column($api_response['merge_fields'], 'name', 'tag'));
            }
        } catch(Exception $e) {
            $this->set_error($e->getMessage());
            return false;
        }
    }

    public function get_merge_fields_of_member($subscriber_email = null)
    {
        if(!empty($this->error)) { return false; }

        $subscriber_hash = $this->mailchimp->subscriberHash($subscriber_email);

        try {
            $api_url = "lists/{$this->list_id}/members/{$subscriber_hash}?fields=merge_fields";
            $api_response = $this->mailchimp->get($api_url);
        } catch(Exception $e) {
            $this->set_error($e->getMessage());
            return false;
        }

        if (array_key_exists('status', $api_response) && $api_response['status'] = 404) {
            $this->set_error("Email is not present in the list. Please crosscheck and retry");
            return false;
        }

        if (!array_key_exists('merge_fields', $api_response)) {
            $this->set_error("Failed to fetch information from server");
            return false;
        }

        $merge_fields = $api_response['merge_fields'];
        if (!is_array($merge_fields) || sizeof($merge_fields) < 1) {
            $this->set_error("Failed to fetch information from server");
            return false;
        }

        return $merge_fields;
    }

    public function set_error($error = '')
    {
        $this->error = $error;
    }

    public function get_error()
    {
        return $this->error;
    }
}
