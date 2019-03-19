[titleEn]: <>(Plugin - Base Class)
[wikiUrl]: <>(../platform/extending-the-platform/plugins/plugin-base-class?category=platform-en/extending-the-platform/plugins)

# Overview
In this guide, you will learn what a plugin base class is and how to use it.
Below you'll find a valid plugin file structure.

```
custom
└── plugins
    └──  BaseClass
         ├── BaseClass.php
         └── composer.json
```
*File Structure*

Read more about the `composer.json` file [here](./050-plugin-information.md).

# Base Class
Your plugin base class is used, to configure your plugin and manage plugin lifecycle events such as `update` and `install`.
Every plugin base class must extend from the `Shopware\Core\Framework\Plugin` class.
Take a look at the most minimalistic plugin base class:

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;

class BaseClass extends Plugin
{
}
```
*BaseClass.php*

Right now you got a valid base class without any functionality.
Below you find lists of methods you can overwrite in your plugin base class.

## Plugin lifecycle

Those methods are used to deal with certain lifecycle events of your plugin.
They are only executed once, when the user triggers one of those actions.
The core Shopware DI container is available for all of them.

|Method                           | Arguments                                                      | Usage                                     |
|---------------------------------|----------------------------------------------------------------|-------------------------------------------|
| [install()](#install())         | [InstallContext](./040-plugin-contexts.md#installContext)      | Called while your plugin gets installed   |
| [postInstall()](#postInstall()) | [InstallContext](./040-plugin-contexts.md#installContext)      | Called after your plugin got installed    |
| [update()](#update())           | [UpdateContext](./040-plugin-contexts.md#updateContext)        | Called while your plugin gets updated     |
| [postUpdate()](#postUpdate())   | [UpdateContext](./040-plugin-contexts.md#updateContext)        | Called after your plugin got updated      |
| [activate()](#activate())       | [ActivateContext](./040-plugin-contexts.md#activateContext)    | Called while your plugin gets activated   |
| [deactivate()](#deactivate())   | [DeactivateContext](./040-plugin-contexts.md#deactivateContext)| Called while your plugin gets deactivated |
| [uninstall()](#uninstall())     | [UninstallContext](./040-plugin-contexts.md#uninstallContext)  | Called while your plugin gets uninstalled |

Also have a look at this diagram for a more detailed overview of the lifecycle methods:
![Plugin lifecycle](./_img/plugin-lifecycle.png)

### install()
You can use this method to execute code you need to run while your plugin gets installed.
For example, you could use this method to create a new payment method.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;

class BaseClass extends Plugin
{
    public function install(InstallContext $context): void
    {
        // your code you need to execute while installation
    }
}
```
*Please note, if your code fails or throws an exception, your plugin will not be installed.*

### postInstall()
You can use this method to execute code you need to run after your plugin is installed and migrations have run.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;

class BaseClass extends Plugin
{
    public function postInstall(InstallContext $context): void
    {
        //your code you need to execute after your plugin gets installed
    }
}
```
*Please note, if your code fails or throws an exception, your plugin will not be activated in the same step.*

### update()
You can use this method to execute code you need to run while your plugin gets updated.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;

class BaseClass extends Plugin
{
    public function update(UpdateContext $context): void
    {
       // your code you need to execute while your plugin gets updated
    }

}
```
*Please note, if your code fails or throws an exception, your plugin will not be updated.*

### postUpdate()
You can use this method, to execute code you need to run after your plugin is updated and migrations have run.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;

class BaseClass extends Plugin
{
    public function postUpdate(UpdateContext $context): void
    {
        // your code you need to execute after your plugin is updated
    }
}
```

### activate()
You can use this method, to execute code you need to run while your plugin gets activated.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;

class BaseClass extends Plugin
{
    public function activate(ActivateContext $context): void
    {
        // your code you need to execute while your plugin gets activated
    }

}
```
*Please note, if your code fails or throws an exception your plugin will not be activated.*

### deactivate()
You can use this method, to execute code you need to run while your plugin gets deactivated.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;

class BaseClass extends Plugin
{
    public function deactivate(DeactivateContext $context): void
    {
        // your code you need to run while your plugin gets deactivated
    }
}
```
*Please note, if your code fails or throws an exception, your plugin will not be deactivated.*

### uninstall()
You can use this method, to execute code you need to run while your plugin gets uninstalled.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class BaseClass extends Plugin
{
    public function uninstall(UninstallContext $context): void
    {
        // your code you need to execute while your plugin gets uninstalled
    }
}
```
*Please note, if your code fails or throws an exception, your plugin will not be uninstalled.*

## Configuring your plugin

Those methods are called to configure your plugin, e.g. configuring the path for your `routes.xml` file or to add `ActionEvents`.
These are executed with each request, so be careful with them, due to performance and fatal error issues.

|Method                                               | Arguments                                   | Usage                                                                                                 | Container available |
|-----------------------------------------------------|---------------------------------------------|-------------------------------------------------------------------------------------------------------|---------------------|
| [build()](#build())                                 | ContainerBuilder                            | Called while Symfony builds the [DI container](https://symfony.com/doc/current/service_container.html)|      Partially      |
| [configureRoutes()](#configureRoutes())             | RouteCollectionBuilder, string $environment | Called on each kernel boot to register your controller routes                                         |         No          |
| [getMigrationNamespace()](#getMigrationNamespace()) | N/A                                         | Called whenever migrations get executed to add your migration namespace to the migration collection   |         Yes         |
| [getContainerPrefix()](#getContainerPrefix())       | N/A                                         | Prefixes automatic service registrations like filesystems for example                                 |      Partially      |
| [getActionEvents()](#getActionEvents())             | N/A                                         | Registers action events for your plugin                                                               |      Partially      |

### build()
You can use this method, to build the `Dependency Injection Container` (DIC) how you need it.
For example, you can load your own `services.xml` into the DIC.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class BaseClass extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection/'));
        $loader->load('services.xml');
    }
}
```
*Please note, if your code fails or throws an exception, the `Symfony Kernel` will no longer be able to boot.*

### configureRoutes()
You can use this method, to configure routing for your plugin.
Per default, you can configure your routes in `YourPlugin/Resources/routes.xml`.
Click [here](./110-custom-api-routes.md#route-configuration) if you want to learn more.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;
use Symfony\Component\Routing\RouteCollectionBuilder;

class BaseClass extends Plugin
{
    public function configureRoutes(RouteCollectionBuilder $routes, string $environment): void
    {
        $routes->import(__DIR__ . '/my_routes.xml');
    }
}
```
*Please note, if your code fails or throws an exception, the `Symfony Kernel` will no longer be able to boot.*

### getMigrationNamespace()
You can use this method, to configure a custom migration namespace.
For your example plugin `BaseClass` the default migration namespace would be `BaseClass\Migration`.
If you're not familiar with plugin migrations yet, make sure to read our guide about our [plugin migration system](./080-plugin-migrations.md).

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;

class BaseClass extends Plugin
{
    public function getMigrationNamespace(): string
    {
        return 'BaseClass\MyMigrationNamespace';
    }
}
```
*Please note, if your code fails or throws an exception, your plugin migrations will no longer work.*

### getContainerPrefix()
You can use this method, to configure your own container prefix.
For your example plugin `BaseClass` the default container prefix would be `base_class`.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;

class BaseClass extends Plugin
{
    public function getContainerPrefix(): string
    {
        return 'my_container_prefix';
    }
}
```

## Plugin boot process

This method is executed at a very early point of the Shopware stack, but only if your plugin is active already.

|Method                           | Arguments                   | Usage                                      | Container available |
|---------------------------------|-----------------------------|--------------------------------------------|---------------------|
| [boot()](#boot())               | N/A                         | Called while the Shopware kernel is booted |         Yes         |

## boot()
Boots your plugin and is called when the kernel gets booted.
The container is available here already.

```php
<?php declare(strict_types=1);

namespace BaseClass;

use Shopware\Core\Framework\Plugin;

class BaseClass extends Plugin
{
    public function boot(): void
    {
        parent::boot();
    }
}
```