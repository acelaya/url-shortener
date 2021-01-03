<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core\Paginator\Adapter;

use Happyr\DoctrineSpecification\Specification\Specification;
use Laminas\Paginator\Adapter\AdapterInterface;
use Shlinkio\Shlink\Core\Model\ShortUrlsParams;
use Shlinkio\Shlink\Core\Repository\ShortUrlRepositoryInterface;
use Shlinkio\Shlink\Rest\Entity\ApiKey;

class ShortUrlRepositoryAdapter implements AdapterInterface
{
    private ShortUrlRepositoryInterface $repository;
    private ShortUrlsParams $params;
    private ?ApiKey $apiKey;

    public function __construct(ShortUrlRepositoryInterface $repository, ShortUrlsParams $params, ?ApiKey $apiKey)
    {
        $this->repository = $repository;
        $this->params = $params;
        $this->apiKey = $apiKey;
    }

    public function getItems($offset, $itemCountPerPage): array // phpcs:ignore
    {
        return $this->repository->findList(
            $itemCountPerPage,
            $offset,
            $this->params->searchTerm(),
            $this->params->tags(),
            $this->params->orderBy(),
            $this->params->dateRange(),
            $this->resolveSpec(),
        );
    }

    public function count(): int
    {
        return $this->repository->countList(
            $this->params->searchTerm(),
            $this->params->tags(),
            $this->params->dateRange(),
            $this->resolveSpec(),
        );
    }

    private function resolveSpec(): ?Specification
    {
        return $this->apiKey !== null ? $this->apiKey->spec() : null;
    }
}
