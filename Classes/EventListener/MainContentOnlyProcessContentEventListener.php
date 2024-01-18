<?php

namespace FRUIT\StaticExport\EventListener;

use FRUIT\StaticExport\Event\ProcessContentEvent;

class MainContentOnlyProcessContentEventListener
{

    const REGEX_MAIN_EXTRACT = '/<main[^>]*>(.*)<\/main>/s';

    const REGEX_NAV_REMOVE = '/<nav[^>]*>.*<\/nav>/sU';

    public function __invoke(ProcessContentEvent $event)
    {
        if (preg_match(self::REGEX_MAIN_EXTRACT, $event->getContent(), $matches)) {
            $event->setContent($matches[1]);
        }

        $event->setContent(preg_replace(self::REGEX_NAV_REMOVE, '', $event->getContent()));
    }
}
