<?php

/**
 * BCP
 *
 * @package ${NAMESPACE}
 * @license Proprietary Software
 * @author  Pavlo Cherniavskyi
 */

declare(strict_types=1);

namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{
    public function __construct(
        private MarkdownParserInterface $markdownParser,
        private CacheInterface          $cache,
        private bool $isDebug,
        private LoggerInterface $markdownLogger
    )
    {
    }

    public function parse(string $string): string
    {
        $this->markdownLogger->info("");

        if (!$this->isDebug) {
            return $this->markdownParser->transformMarkdown($string);
        }

        return $this->cache->get('markdown_' . md5($string), function () use ($string) {
            return $this->markdownParser->transformMarkdown($string);
        });
    }
}