<?php

namespace FRUIT\StaticExport\Service;

use FRUIT\StaticExport\Event\ProcessContentEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Collector
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function collect(RequestInterface $request, ResponseInterface $response)
    {
        /** @var \TYPO3\CMS\Core\Http\Uri $uri */
        $uri = clone $request->getUri();
        $uri = $uri->withHost('')->withScheme('')->withPort(null);
        if ('' === (string)pathinfo($uri->getPath(), \PATHINFO_EXTENSION)) {
            $uri = $uri->withPath(rtrim($uri->getPath(), '/') . '/index.html');
        }


        /** @var PathService $pathService */
        $pathService = GeneralUtility::makeInstance(PathService::class);

        $targetFile = $pathService->getCollectFolder(). '/' . ltrim((string)$uri, '/');

        if (is_file($targetFile)) {
            unlink($targetFile);
        }

        $content = (string)$response->getBody();

        $event = new ProcessContentEvent($content);
        $this->eventDispatcher->dispatch($event);

        GeneralUtility::mkdir_deep(pathinfo($targetFile, \PATHINFO_DIRNAME));
        GeneralUtility::writeFile($targetFile, $event->getContent());
    }

    public function cleanup()
    {
        $pathService = GeneralUtility::makeInstance(PathService::class);
        GeneralUtility::rmdir($pathService->getCollectFolder(), true);
    }
}
