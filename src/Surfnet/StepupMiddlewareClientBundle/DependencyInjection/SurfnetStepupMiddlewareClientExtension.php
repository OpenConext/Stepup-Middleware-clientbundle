<?php

declare(strict_types = 1);

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupMiddlewareClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SurfnetStepupMiddlewareClientExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('library.yml');
        $loader->load('services.yml');

        $this->configureMiddlewareApiCredentials($config, $container);
        $this->configureMiddlewareCommandApiUrl($config, $container);
        $this->configureMiddlewareReadApiClient($config, $container);
    }

    private function configureMiddlewareApiCredentials(array $config, ContainerBuilder $container): void
    {
        $commandService = $container->getDefinition('surfnet_stepup_middleware_client.library.service.command');
        $commandService->replaceArgument(1, $config['authorisation']['username']);
        $commandService->replaceArgument(2, $config['authorisation']['password']);
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Definition
     */
    private function configureMiddlewareCommandApiUrl(array $config, ContainerBuilder $container): void
    {
        $guzzle = $container->getDefinition('surfnet_stepup_middleware_client.guzzle.commands');
        $guzzle->replaceArgument(0, ['base_uri' => $config['url']['command_api']]);
    }

    private function configureMiddlewareReadApiClient(array $config, ContainerBuilder $container): void
    {
        $guzzle = $container->getDefinition('surfnet_stepup_middleware_client.guzzle.api');
        $guzzle->replaceArgument(
            0,
            [
                'base_uri' => $config['url']['api'],
                'auth'    => [
                    $config['authorisation']['username'],
                    $config['authorisation']['password'],
                    'basic'
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );
    }
}
