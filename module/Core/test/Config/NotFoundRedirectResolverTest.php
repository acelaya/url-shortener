<?php

declare(strict_types=1);

namespace ShlinkioTest\Shlink\Core\Config;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Uri;
use Mezzio\Router\Route;
use Mezzio\Router\RouteResult;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Shlinkio\Shlink\Core\Action\RedirectAction;
use Shlinkio\Shlink\Core\Config\NotFoundRedirectConfigInterface;
use Shlinkio\Shlink\Core\Config\NotFoundRedirectResolver;
use Shlinkio\Shlink\Core\ErrorHandler\Model\NotFoundType;
use Shlinkio\Shlink\Core\Options\NotFoundRedirectOptions;
use Shlinkio\Shlink\Core\Util\RedirectResponseHelperInterface;

class NotFoundRedirectResolverTest extends TestCase
{
    use ProphecyTrait;

    private NotFoundRedirectResolver $resolver;
    private ObjectProphecy $helper;
    private NotFoundRedirectConfigInterface $config;

    protected function setUp(): void
    {
        $this->helper = $this->prophesize(RedirectResponseHelperInterface::class);
        $this->resolver = new NotFoundRedirectResolver($this->helper->reveal());

        $this->config = new NotFoundRedirectOptions([
            'invalidShortUrl' => 'invalidShortUrl',
            'regular404' => 'regular404',
            'baseUrl' => 'baseUrl',
        ]);
    }

    /**
     * @test
     * @dataProvider provideRedirects
     */
    public function expectedRedirectionIsReturnedDependingOnTheCase(
        NotFoundType $notFoundType,
        string $expectedRedirectTo,
    ): void {
        $expectedResp = new Response();
        $buildResp = $this->helper->buildRedirectResponse($expectedRedirectTo)->willReturn($expectedResp);

        $resp = $this->resolver->resolveRedirectResponse($notFoundType, $this->config);

        self::assertSame($expectedResp, $resp);
        $buildResp->shouldHaveBeenCalledOnce();
    }

    public function provideRedirects(): iterable
    {
        yield 'base URL with trailing slash' => [
            $this->notFoundType(ServerRequestFactory::fromGlobals()->withUri(new Uri('/'))),
            'baseUrl',
        ];
        yield 'base URL without trailing slash' => [
            $this->notFoundType(ServerRequestFactory::fromGlobals()->withUri(new Uri(''))),
            'baseUrl',
        ];
        yield 'regular 404' => [
            $this->notFoundType(ServerRequestFactory::fromGlobals()->withUri(new Uri('/foo/bar'))),
            'regular404',
        ];
        yield 'invalid short URL' => [
            $this->notFoundType($this->requestForRoute(RedirectAction::class)),
            'invalidShortUrl',
        ];
    }

    /** @test */
    public function noResponseIsReturnedIfNoConditionsMatch(): void
    {
        $notFoundType = $this->notFoundType($this->requestForRoute('foo'));

        $result = $this->resolver->resolveRedirectResponse($notFoundType, $this->config);

        self::assertNull($result);
        $this->helper->buildRedirectResponse(Argument::cetera())->shouldNotHaveBeenCalled();
    }

    private function notFoundType(ServerRequestInterface $req): NotFoundType
    {
        return NotFoundType::fromRequest($req, '');
    }

    private function requestForRoute(string $routeName): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals()
            ->withAttribute(
                RouteResult::class,
                RouteResult::fromRoute(
                    new Route(
                        '',
                        $this->prophesize(MiddlewareInterface::class)->reveal(),
                        ['GET'],
                        $routeName,
                    ),
                ),
            )
            ->withUri(new Uri('/abc123'));
    }
}
