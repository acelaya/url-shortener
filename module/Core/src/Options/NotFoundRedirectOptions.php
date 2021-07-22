<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core\Options;

use Laminas\Stdlib\AbstractOptions;
use Shlinkio\Shlink\Core\Config\NotFoundRedirectConfigInterface;

class NotFoundRedirectOptions extends AbstractOptions implements NotFoundRedirectConfigInterface
{
    private ?string $invalidShortUrl = null;
    private ?string $regular404 = null;
    private ?string $baseUrl = null;

    public function invalidShortUrlRedirect(): ?string
    {
        return $this->invalidShortUrl;
    }

    public function hasInvalidShortUrlRedirect(): bool
    {
        return $this->invalidShortUrl !== null;
    }

    protected function setInvalidShortUrl(?string $invalidShortUrl): self
    {
        $this->invalidShortUrl = $invalidShortUrl;
        return $this;
    }

    public function regular404Redirect(): ?string
    {
        return $this->regular404;
    }

    public function hasRegular404Redirect(): bool
    {
        return $this->regular404 !== null;
    }

    protected function setRegular404(?string $regular404): self
    {
        $this->regular404 = $regular404;
        return $this;
    }

    public function baseUrlRedirect(): ?string
    {
        return $this->baseUrl;
    }

    public function hasBaseUrlRedirect(): bool
    {
        return $this->baseUrl !== null;
    }

    protected function setBaseUrl(?string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }
}
