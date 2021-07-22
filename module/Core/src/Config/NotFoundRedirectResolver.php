<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core\Config;

use Psr\Http\Message\ResponseInterface;
use Shlinkio\Shlink\Core\ErrorHandler\Model\NotFoundType;
use Shlinkio\Shlink\Core\Util\RedirectResponseHelperInterface;

class NotFoundRedirectResolver implements NotFoundRedirectResolverInterface
{
    public function __construct(private RedirectResponseHelperInterface $redirectResponseHelper)
    {
    }

    public function resolveRedirectResponse(
        NotFoundType $notFoundType,
        NotFoundRedirectConfigInterface $config
    ): ?ResponseInterface {
        return match (true) {
            $notFoundType->isBaseUrl() && $config->hasBaseUrlRedirect() =>
                // @phpstan-ignore-next-line Create custom PHPStan rule
                $this->redirectResponseHelper->buildRedirectResponse($config->baseUrlRedirect()),
            $notFoundType->isRegularNotFound() && $config->hasRegular404Redirect() =>
                // @phpstan-ignore-next-line Create custom PHPStan rule
                $this->redirectResponseHelper->buildRedirectResponse($config->regular404Redirect()),
            $notFoundType->isInvalidShortUrl() && $config->hasInvalidShortUrlRedirect() =>
                // @phpstan-ignore-next-line Create custom PHPStan rule
                $this->redirectResponseHelper->buildRedirectResponse($config->invalidShortUrlRedirect()),
            default => null,
        };
    }
}
