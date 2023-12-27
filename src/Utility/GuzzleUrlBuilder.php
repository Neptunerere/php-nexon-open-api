<?php

declare(strict_types=1);

namespace Neptunerere\PhpNexonOpenApi\Utility;

use GuzzleHttp\Psr7\Uri;
use Neptunerere\PhpNexonOpenApi\Command\CommandInterface;
use Neptunerere\PhpNexonOpenApi\Utility\UrlBuilderInterface;

class GuzzleUrlBuilder implements UrlBuilderInterface
{
    /**
     * @var string
     */
    protected string $baseUrl = "";

    /**
     * {@inheritdoc}
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function build(CommandInterface $commandInterface)
    {
        $uri = sprintf('%s/%s/%s/%s',
            rtrim($this->getBaseUrl()),
            $commandInterface->getGameName(),
            $commandInterface->getVersion(),
            $commandInterface->getMethod(),
        );

        return new Uri($uri);
    }
}