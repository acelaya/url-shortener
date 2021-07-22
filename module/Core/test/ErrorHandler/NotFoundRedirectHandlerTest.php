<?php

declare(strict_types=1);

namespace ShlinkioTest\Shlink\Core\ErrorHandler;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shlinkio\Shlink\Core\Config\NotFoundRedirectResolverInterface;
use Shlinkio\Shlink\Core\Domain\DomainServiceInterface;
use Shlinkio\Shlink\Core\Entity\Domain;
use Shlinkio\Shlink\Core\ErrorHandler\Model\NotFoundType;
use Shlinkio\Shlink\Core\ErrorHandler\NotFoundRedirectHandler;
use Shlinkio\Shlink\Core\Options\NotFoundRedirectOptions;

class NotFoundRedirectHandlerTest extends TestCase
{
    use ProphecyTrait;

    private NotFoundRedirectHandler $middleware;
    private NotFoundRedirectOptions $redirectOptions;
    private ObjectProphecy $resolver;
    private ObjectProphecy $domainService;
    private ObjectProphecy $next;
    private ServerRequestInterface $req;

    public function setUp(): void
    {
        $this->redirectOptions = new NotFoundRedirectOptions();
        $this->resolver = $this->prophesize(NotFoundRedirectResolverInterface::class);
        $this->domainService = $this->prophesize(DomainServiceInterface::class);

        $this->middleware = new NotFoundRedirectHandler(
            $this->redirectOptions,
            $this->resolver->reveal(),
            $this->domainService->reveal(),
        );

        $this->next = $this->prophesize(RequestHandlerInterface::class);
        $this->req = ServerRequestFactory::fromGlobals()->withAttribute(
            NotFoundType::class,
            $this->prophesize(NotFoundType::class)->reveal(),
        );
    }

    /**
     * @test
     * @dataProvider provideNonRedirectScenarios
     */
    public function nextIsCalledWhenNoRedirectIsResolved(callable $setUp): void
    {
        $expectedResp = new Response();

        $setUp($this->domainService, $this->resolver);
        $handle = $this->next->handle($this->req)->willReturn($expectedResp);

        $result = $this->middleware->process($this->req, $this->next->reveal());

        self::assertSame($expectedResp, $result);
        $handle->shouldHaveBeenCalledOnce();
    }

    public function provideNonRedirectScenarios(): iterable
    {
        yield 'no domain' => [function (ObjectProphecy $domainService, ObjectProphecy $resolver): void {
            $domainService->findByAuthority(Argument::cetera())
                ->willReturn(null)
                ->shouldBeCalledOnce();
            $resolver->resolveRedirectResponse(Argument::cetera())
                ->willReturn(null)
                ->shouldBeCalledOnce();
        }];
        yield 'non-redirecting domain' => [function (ObjectProphecy $domainService, ObjectProphecy $resolver): void {
            $domainService->findByAuthority(Argument::cetera())
                ->willReturn(new Domain(''))
                ->shouldBeCalledOnce();
            $resolver->resolveRedirectResponse(Argument::cetera())
                ->willReturn(null)
                ->shouldBeCalledTimes(2);
        }];
    }

    /** @test */
    public function globalRedirectIsUsedIfDomainRedirectIsNotFound(): void
    {
        $expectedResp = new Response();

        $findDomain = $this->domainService->findByAuthority(Argument::cetera())->willReturn(null);
        $resolveRedirect = $this->resolver->resolveRedirectResponse(
            Argument::type(NotFoundType::class),
            $this->redirectOptions,
        )->willReturn($expectedResp);

        $result = $this->middleware->process($this->req, $this->next->reveal());

        self::assertSame($expectedResp, $result);
        $findDomain->shouldHaveBeenCalledOnce();
        $resolveRedirect->shouldHaveBeenCalledOnce();
        $this->next->handle(Argument::cetera())->shouldNotHaveBeenCalled();
    }

    /** @test */
    public function domainRedirectIsUsedIfFound(): void
    {
        $expectedResp = new Response();
        $domain = new Domain('');

        $findDomain = $this->domainService->findByAuthority(Argument::cetera())->willReturn($domain);
        $resolveRedirect = $this->resolver->resolveRedirectResponse(
            Argument::type(NotFoundType::class),
            $domain,
        )->willReturn($expectedResp);

        $result = $this->middleware->process($this->req, $this->next->reveal());

        self::assertSame($expectedResp, $result);
        $findDomain->shouldHaveBeenCalledOnce();
        $resolveRedirect->shouldHaveBeenCalledOnce();
        $this->next->handle(Argument::cetera())->shouldNotHaveBeenCalled();
    }
}
