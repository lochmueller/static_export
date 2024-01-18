<?php

namespace FRUIT\StaticExport\EventListener;

use FRUIT\StaticExport\Event\ProcessContentEvent;

class MainContentOnlyProcessContentEventListener
{
    public function __invoke(ProcessContentEvent $event)
    {
        var_dump('IN');
        exit();
    }
}
