<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core\Repository;

use Doctrine\Persistence\ObjectRepository;
use Happyr\DoctrineSpecification\Repository\EntitySpecificationRepositoryInterface;
use Happyr\DoctrineSpecification\Specification\Specification;
use Shlinkio\Shlink\Common\Util\DateRange;
use Shlinkio\Shlink\Core\Entity\ShortUrl;
use Shlinkio\Shlink\Core\Model\ShortUrlIdentifier;
use Shlinkio\Shlink\Core\Model\ShortUrlMeta;
use Shlinkio\Shlink\Core\Model\ShortUrlsOrdering;
use Shlinkio\Shlink\Importer\Model\ImportedShlinkUrl;

interface ShortUrlRepositoryInterface extends ObjectRepository, EntitySpecificationRepositoryInterface
{
    public function findList(
        ?int $limit = null,
        ?int $offset = null,
        ?string $searchTerm = null,
        array $tags = [],
        ?ShortUrlsOrdering $orderBy = null,
        ?DateRange $dateRange = null,
        ?Specification $spec = null,
    ): array;

    public function countList(
        ?string $searchTerm = null,
        array $tags = [],
        ?DateRange $dateRange = null,
        ?Specification $spec = null,
    ): int;

    public function findOneWithDomainFallback(string $shortCode, ?string $domain = null): ?ShortUrl;

    public function findOne(ShortUrlIdentifier $identifier, ?Specification $spec = null): ?ShortUrl;

    public function shortCodeIsInUse(ShortUrlIdentifier $identifier, ?Specification $spec = null): bool;

    public function shortCodeIsInUseWithLock(ShortUrlIdentifier $identifier, ?Specification $spec = null): bool;

    public function findOneMatching(ShortUrlMeta $meta): ?ShortUrl;

    public function findOneByImportedUrl(ImportedShlinkUrl $url): ?ShortUrl;

    public function findCrawlableShortCodes(): iterable;
}
