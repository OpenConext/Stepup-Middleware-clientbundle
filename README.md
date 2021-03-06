# Step-up Middleware Client Bundle
[![Build Status](https://travis-ci.org/OpenConext/Stepup-Middleware-clientbundle.svg)](https://travis-ci.org/OpenConext/Stepup-Middleware-clientbundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OpenConext/Stepup-Middleware-clientbundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/OpenConext/Stepup-Middleware-clientbundle/?branch=develop) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/3fef2be8-1351-4fbb-b6e8-00296c1d8d36/mini.png)](https://insight.sensiolabs.com/projects/3fef2be8-1351-4fbb-b6e8-00296c1d8d36)

A Symfony2 bundle to consume the Step-up Middleware API. See [Stepup-Deploy](https://github.com/OpenConext/Stepup-Deploy) for an overview. 

## Requirements

 * PHP 5.6+ or PHP7
 * [Composer](https://getcomposer.org/)
 * A working [Gateway](https://github.com/OpenConext/Stepup-Gateway)
 * Working [Middleware](https://github.com/OpenConext/Stepup-Middleware)

## Installation

 * Add the package to your Composer file
    ```sh
    composer require surfnet/stepup-middleware-client-bundle
    ```

 * Add the bundle to your kernel in `app/AppKernel.php`
    ```php
    public function registerBundles()
    {
        // ...
        $bundles[] = new Surfnet\StepupMiddlewareClientBundle\SurfnetStepupMiddlewareClientBundle;
    }
    ```

## Configuration

```yaml
surfnet_stepup_middleware_client:
    authorisation:
        username: john
        password: doe
    url:
        command_api: http://middleware.tld/command
```

## Usage

### Executing commands

```php
# In the context of a Symfony2 controller action
$command = new \Surfnet\StepupMiddlewareClientBundle\Identity\Command\CreateIdentityCommand();
$command->id = \Surfnet\StepupMiddlewareClientBundle\Uuid\Uuid::generate();
$command->nameId = \Surfnet\StepupMiddlewareClientBundle\Uuid\Uuid::generate();

/** @var \Surfnet\StepupMiddlewareClientBundle\Service\CommandService $service */
$service = $this->get('surfnet_stepup_middleware_client.service.command');
$result = $service->execute($command);
```

### Reading DTOs

```php
/** @var \Surfnet\StepupMiddlewareClientBundle\Identity\Service\IdentityService $service */
$service = $container->get('surfnet_stepup_middleware_client.identity.service.identity');
/** @var null|\Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity */
$identity = $service->get($id);
```

## Release strategy
Please read: https://github.com/OpenConext/Stepup-Deploy/wiki/Release-Management fro more information on the release strategy used in Stepup projects.
