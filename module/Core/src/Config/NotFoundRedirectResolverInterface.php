<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core\Config;

use Psr\Http\Message\ResponseInterface;
use Shlinkio\Shlink\Core\ErrorHandler\Model\NotFoundType;

interface NotFoundRedirectResolverInterface
{
    public function resolveRedirectResponse(
        NotFoundType $notFoundType,
        NotFoundRedirectConfigInterface $config
    ): ?ResponseInterface;
}
