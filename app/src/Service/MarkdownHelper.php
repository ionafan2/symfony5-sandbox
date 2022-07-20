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
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{
    public function __construct(
        private MarkdownParserInterface $markdownParser,
        private CacheInterface          $cache,
        private bool $isDebug,
        private LoggerInterface $dLogger,
        private Security $security
    )
    {
    }

    public function parse(string $string): string
    {
        if (stripos($string, 'cat') !== false) {
            $this->dLogger->info('Meow!');
        }

        if ($this->security->getUser()) {
            $this->dLogger->info('Rendering markdown for {user}', [
                'user' => $this->security->getUser()->getUserIdentifier()
            ]);
        }

        if ($this->isDebug) {
            return $this->markdownParser->transformMarkdown($string);
        }

        return $this->cache->get('markdown_' . md5($string), function () use ($string) {
            return $this->markdownParser->transformMarkdown($string);
        });
    }
}