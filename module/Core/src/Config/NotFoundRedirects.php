<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core\Config;

final class NotFoundRedirects
{
    public function __construct(
        private ?string $baseUrlRedirect = null,
        private ?string $regular404Redirect = null,
        private ?string $invalidShortUrlRedirect = null,
    ) {
    }

    public function baseUrlRedirect(): ?string
    {
        return $this->baseUrlRedirect;
    }

    public function regular404Redirect(): ?string
    {
        return $this->regular404Redirect;
    }

    public function invalidShortUrlRedirect(): ?string
    {
        return $this->invalidShortUrlRedirect;
    }
}
