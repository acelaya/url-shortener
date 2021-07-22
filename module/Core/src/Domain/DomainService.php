<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Shlinkio\Shlink\Core\Config\NotFoundRedirects;
use Shlinkio\Shlink\Core\Domain\Model\DomainItem;
use Shlinkio\Shlink\Core\Domain\Repository\DomainRepositoryInterface;
use Shlinkio\Shlink\Core\Entity\Domain;
use Shlinkio\Shlink\Core\Exception\DomainNotFoundException;
use Shlinkio\Shlink\Core\Options\NotFoundRedirectOptions;
use Shlinkio\Shlink\Rest\ApiKey\Role;
use Shlinkio\Shlink\Rest\Entity\ApiKey;

use function Functional\map;

class DomainService implements DomainServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private string $defaultDomain,
        private NotFoundRedirectOptions $redirectOptions,
    ) {
    }

    /**
     * @return DomainItem[]
     */
    public function listDomains(?ApiKey $apiKey = null): array
    {
        /** @var DomainRepositoryInterface $repo */
        $repo = $this->em->getRepository(Domain::class);
        $domains = $repo->findDomainsWithout($this->defaultDomain, $apiKey);
        $mappedDomains = map($domains, fn (Domain $domain) => DomainItem::forExistingDomain($domain));

        if ($apiKey?->hasRole(Role::DOMAIN_SPECIFIC)) {
            return $mappedDomains;
        }

        return [
            DomainItem::forDefaultDomain($this->defaultDomain, $this->redirectOptions),
            ...$mappedDomains,
        ];
    }

    /**
     * @throws DomainNotFoundException
     */
    public function getDomain(string $domainId): Domain
    {
        /** @var Domain|null $domain */
        $domain = $this->em->find(Domain::class, $domainId);
        if ($domain === null) {
            throw DomainNotFoundException::fromId($domainId);
        }

        return $domain;
    }

    public function findByAuthority(string $authority): ?Domain
    {
        $repo = $this->em->getRepository(Domain::class);
        return $repo->findOneBy(['authority' => $authority]);
    }

    public function getOrCreate(string $authority): Domain
    {
        $domain = $this->findByAuthority($authority) ?? new Domain($authority);

        $this->em->persist($domain);
        $this->em->flush();

        return $domain;
    }

    public function configureNotFoundRedirects(string $authority, NotFoundRedirects $notFoundRedirects): Domain
    {
        $domain = $this->getOrCreate($authority);
        $domain->configureNotFoundRedirects($notFoundRedirects);

        $this->em->flush();

        return $domain;
    }
}
