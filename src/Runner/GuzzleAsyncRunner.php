<?php

declare(strict_types=1);

namespace Neptunerere\PhpNexonOpenApi\Runner;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use Neptunerere\PhpNexonOpenApi\Command\CommandInterface;
use Neptunerere\PhpNexonOpenApi\Utility\UrlBuilderInterface;
use Neptunerere\PhpNexonOpenApi\Runner\{
    AbstractRunner,
    RunnerInterface
};

class GuzzleAsyncRunner extends AbstractRunner implements RunnerInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var UrlBuilderInterface
     */
    protected $urlBuilder;

    /**
     * @param ClientInterface $client
     * @param UrlBuilderInterface $urlBuilder
     */
    public function __construct(ClientInterface $client, UrlBuilderInterface $urlBuilder)
    {
        $this->client = $client;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     *
     * @return PromiseInterface
     */
    public function run(CommandInterface $command, $result = null)
    {
        $key = $command->getRequestMethod() === 'GET' ? 'query' : 'body';
        $options = [$key => []];

        if(!empty($params = $command->getParams())) {
            $options[$key] = array_merge($options[$key], $params);
        }

        if($config = $this->getConfig()) {
            if(!empty($config->getApiKey())) {
                $options['headers']['x-nxopen-api-key'] = $config->getApiKey();
            }
            
            $this->urlBuilder->setBaseUrl($config->getBaseApiUrl());
        }

        $request = new Request(
            $command->getRequestMethod(),
            $this->urlBuilder->build($command)
        );

        return $this->client->sendAsync($request, $options);
    }
}