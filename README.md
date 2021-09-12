## Vine

[![Tests](https://github.com/ketyl/vine/actions/workflows/tests.yml/badge.svg)](https://github.com/ketyl/vine/actions/workflows/tests.yml)

Vine is a simple PHP 8 micro-framework, written from the ground-up. This is more of a learning experience for me and is **not** intended to be production-ready, nor is it going to be in the future.

Inspired by popular PHP frameworks such as Laravel and Slim, Vine maintains a focus on developer experience. Its API is designed to be simple, intuitive, and consistent, while using as little "magic" as possible.

### Features

- [x] Flexible routing engine
- [x] Service container
- [x] Basic view support

### Roadmap

- [ ] Dependency injection
- [ ] Multiple return types
- [ ] Basic templating engine
- [ ] Basic middleware support
- [ ] Adherence to PSR standards (routing, middleware, etc.)

### Installation

The installation of Vine is like any other composer package. First, include the package as a dependency:

```shell
composer require ketyl/vine
```

Once installed, you may create an instance of `Ketyl\Vine\App`, add your routes, and run the application:

```php
// Create a new application instance
$app = new App();
$router = $app->router();

// Anonymous function
$router->get('/', fn () => 'Hello, world!');

// Class-based route using a method
$router->get('/posts', [PostController::class, 'index']);

// Class-based route using __invoke
$router->get('/download', DownloadController::class);

// Route parameters
$router->get('/param/{foo}', fn ($foo) => $foo);

// Route parameters with RegEx restriction
$router->get('/regex/{foo:\d+}', fn ($foo) => $foo);

// Return a view
$router->get('/view', fn () => view(__DIR__ . '/../views/index.html'));

// Attempt to match the request's URI with the registered routes
$app->run();
```
