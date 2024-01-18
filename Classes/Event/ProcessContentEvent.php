<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Event;

final class ProcessContentEvent
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
