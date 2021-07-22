<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core\Domain\Model;

use JsonSerializable;
use Shlinkio\Shlink\Core\Config\NotFoundRedirectConfigInterface;
use Shlinkio\Shlink\Core\Entity\Domain;

final class DomainItem implements JsonSerializable
{
    private function __construct(
        private string $authority,
        private NotFoundRedirectConfigInterface $notFoundRedirectConfig,
        private bool $isDefault
    ) {
    }

    public static function forExistingDomain(Domain $domain): self
    {
        return new self($domain->getAuthority(), $domain, false);
    }

    public static function forDefaultDomain(string $authority, NotFoundRedirectConfigInterface $config): self
    {
        return new self($authority, $config, true);
    }

    public function jsonSerialize(): array
    {
        return [
            'domain' => $this->authority,
            'isDefault' => $this->isDefault,
        ];
    }

    public function toString(): string
    {
        return $this->authority;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function notFoundRedirectConfig(): NotFoundRedirectConfigInterface
    {
        return $this->notFoundRedirectConfig;
    }
}
