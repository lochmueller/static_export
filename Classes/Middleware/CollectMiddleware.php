<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Middleware;

use FRUIT\StaticExport\Service\Collector;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SFC\Staticfilecache\Event\PreGenerateEvent;
use SFC\Staticfilecache\Service\ConfigurationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class CollectMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {


        $response = $handler->handle($request);

        if ($response->hasHeader('X-SFC-Cachable')) {
            $cachable = (bool) $response->getHeaderLine('X-SFC-Cachable') || true;
            if ($cachable) {
                GeneralUtility::makeInstance(Collector::class)->collect($request, $response);
            }
        }


        return $response;
    }
}
