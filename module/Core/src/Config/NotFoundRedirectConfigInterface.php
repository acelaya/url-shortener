<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core\Config;

interface NotFoundRedirectConfigInterface
{
    public function invalidShortUrlRedirect(): ?string;

    public function hasInvalidShortUrlRedirect(): bool;

    public function regular404Redirect(): ?string;

    public function hasRegular404Redirect(): bool;

    public function baseUrlRedirect(): ?string;

    public function hasBaseUrlRedirect(): bool;
}
