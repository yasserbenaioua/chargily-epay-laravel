<?php

namespace YasserBenaioua\Chargily;

use Illuminate\Support\Str;
use YasserBenaioua\Chargily\Validators\RedirectUrlConfigurationsValidator;

class Configuration
{
    /**
     * @var array configurations
     * Store initial configuration array
     */
    protected $configurations = [];

    /**
     * @var string configPrefix
     * store the prefix of desired configuration , get from payment client email , the prefix is payment
     */
    public ?string $configPrefix = null;

    /**
     * @param array configurations
     */
    public function __construct(array $configurations)
    {
        if (! array_key_exists('urls', $configurations)) {
            $configurations['urls'] = $this->urls();
        }
        if (! array_key_exists('options', $configurations)) {
            $configurations['options'] = $this->defualtConfiguration();
        }
        $this->configurations = $configurations;
    }

    /**
     * @return mixed
     * This method is responsible for dynamic get configurations.
     *
     * @throws \Exception
     */
    public function __call($name, $arguments): mixed
    {
        $name = Str::after($name, 'get');
        if (is_null($name)) {
            throw new \Exception('Undefined getter');
        }
        $method = Str::snake($name, '_');
        if ($this->configPrefix !== null) {
            if (! array_key_exists($method, $this->configurations[$this->configPrefix])) {
                throw new \Exception('Undefined configuration '.$this->configPrefix.'.'.$method);
            }
            $value = $this->configurations[$this->configPrefix][$method];
        } else {
            if (! array_key_exists($method, $this->configurations)) {
                throw new \Exception('Undefined configuration '.$method);
            }
            $value = $this->configurations[$method];
        }
        $this->configPrefix = null;

        return $value;
    }

    /**
     * @method from
     *
     * @param string key
     * Set the desired prefix
     * @return Configuration
     */
    public function from(string $key): Configuration
    {
        $this->configPrefix = $key;

        return $this;
    }

    /**
     * @method validateRedirectConfigurations
     * This method will validate the redirect configuration
     *
     * @return array
     */
    public function validateRedirectConfigurations(): array
    {
        return (new RedirectUrlConfigurationsValidator($this->configurations, true))->validate();
    }

    /**
     * @method urls
     * Store the default config for options
     *
     * @return array
     */
    public function urls(): array
    {
        return [
            'back_url'      => config('chargily.back_url'),
            'webhook_url'   => config('chargily.webhook_url')
        ];
    }
    /**
     * @method defualtConfiguration
     * Store the default config for options
     *
     * @return array
     */
    public function defualtConfiguration(): array
    {
        return [
            'headers' => [],
            'timeout' => 20,
        ];
    }
}
