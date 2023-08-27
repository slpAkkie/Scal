# Uwi Loader

Реализация автозагрузчика классов PHP, в соответствии со спецификацией PSR-4

## Использование

Для подключения загрузчика в свой код, используте следующий пример:

```php
require_once __DIR__ . '/uwi-loader/Loader.php';

Uwi\Loader::register();
Uwi\Loader::fromJson(__DIR__ . '/Loader.json');
```

Пример файла конфигурации:

```json
{
    "psr-4": {
        "Acme\\Log\\Writer\\": "./acme-log-writer/lib/",
        "Aura\\Web\\": "/path/to/aura-web/src/",
        "Symfony\\Core\\": "./vendor/Symfony/Core/",
        "Zend\\": [
            "/usr/includes/Zend/"
        ]
    }
}
```
