<?php

namespace YasserBenaioua\Chargily;

class Chargily
{
    /**
     * configurations
     *
     * @var Configurations
     */
    protected Configuration $configurations;

    /**
     * cachedUrl
     *
     * @var null|string
     */
    protected ?string $cachedRedirectUrl = null;

    /**
     * __construct
     *
     * @param  array|Configurations  $configurations
     * @return void
     */
    public function __construct(array|Configuration $configurations)
    {
        if (is_array($configurations)) {
            $this->configurations = new Configuration($configurations);
        } else {
            $this->configurations = $configurations;
        }
    }

    /**
     * getRedirectUrl
     *
     * @return null|string
     */
    public function getRedirectUrl(): string
    {
        return $this->cachedRedirectUrl = ($this->cachedRedirectUrl) ? $this->cachedRedirectUrl : (new RedirectUrl($this->configurations))->getRedirectUrl();
    }
}
