<?php

namespace App\Twig;

use App\Service\MarkdownHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MarkdownExtension extends AbstractExtension
{
    public function __construct(
        private MarkdownHelper $markdownHelper
    )
    {
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('markdown_parse', [$this, 'doSomething'], ['is_safe' => ['html']]),
        ];
    }

    public function doSomething($value)
    {
        return $this->markdownHelper->parse($value);
    }
}
