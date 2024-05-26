# Cache warm-up via console 

PHP package for warming up cache on sites via http requests.

### Install
```bash
composer require --dev renakdup/cache-warm-up
```

### Run
```bash
./vendor/bin/cache-warmup https://wp-yoda.com/sitemap_index.xml -c 5 -d 2
```

### Roadmap
- [x] Release composer package.
- [x] Support concurrency option.
- [x] Support delay option.
- [ ] Add request handlers.
- [ ] Add formatters.
- [ ] Add filelogger.
- [ ] Add opportunity to register custom logger.
- [ ] Add Unit tests.
- [ ] Add exclude patterns.
- [ ] Add hooks for extending.
- [ ] Add phpcs
- [ ] Add phpstan
- [ ] Add github action runs: phpcs/phpstan
- [ ] Add deploybot.

