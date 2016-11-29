<?php

namespace Statamic\Addons\Fetch;

use Statamic\Extend\Extensible;

class FetchAuth
{
    use Extensible;

    public function isAuth()
    {
        if ($this->getConfig('enable_api_key', false) && ! $this->checkApiKey()) {
            return false;
        }

        if (! empty($this->getConfig('ip_whitelist', [])) && ! $this->checkRemoteAddr()) {
            return false;
        }

        if (! empty($this->getConfig('domain_whitelist', [])) && ! $this->checkRemoteDomain()) {
            return false;
        }

        return true;
    }

    private function checkRemoteDomain()
    {
        if (in_array($_SERVER['HTTP_HOST'], $this->getConfig('domain_whitelist', []))) {
            return true;
        }

        return false;
    }

    private function checkRemoteAddr()
    {
        if (in_array($_SERVER['REMOTE_ADDR'], $this->getConfig('ip_whitelist', []))) {
            return true;
        }

        return false;
    }

    private function checkApiKey()
    {
        if (request('api_key') == $this->getConfig('api_key')) {
            return true;
        }

        return false;
    }
}
