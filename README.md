# Step-up Middleware Client Bundle
[![Build status](https://github.com/OpenConext/Stepup-Middleware-clientbundle/actions/workflows/test-integration.yml/badge.svg)](https://github.com/OpenConext/Stepup-Middleware-clientbundle/actions/workflows/test-integration.yml)

A Symfony 6 bundle to consume the Step-up Middleware API. See [Stepup-Deploy](https://github.com/OpenConext/Stepup-Deploy) for an overview. 

## Requirements

 * PHP 8.2 or higher
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
Please read: https://github.com/OpenConext/Stepup-Deploy/wiki/Release-Management for more information on the release strategy used in Stepup projects.
