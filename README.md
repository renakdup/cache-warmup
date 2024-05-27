# Cache warm-up via console 

_Support: PHP ^8.0, PHP 7.4 just in `0.1.*` versions_.  

PHP package for warming up cache on sites via http requests.

### Install
PHP 8.0 and above
```bash
composer require --dev renakdup/cache-warm-up
```

! PHP 7.4 available just in this branch
```bash
composer require --dev renakdup/cache-warm-up:0.1.*
```

### Run
```bash
./vendor/bin/cache-warmup https://wp-yoda.com/sitemap_index.xml -c 5 -d 2
```
It would looks like
```bash
andreipisarevskii$ ./vendor/bin/cache-warmup https://wp-yoda.com/sitemap_index.xml -c 5 -d 2
200 | https://wp-yoda.com/sitemap_index.xml
200 | https://wp-yoda.com/post-sitemap.xml
200 | https://wp-yoda.com/post_tag-sitemap.xml
200 | https://wp-yoda.com/notices-sitemap.xml
200 | https://wp-yoda.com/category-sitemap.xml
200 | https://wp-yoda.com/page-sitemap.xml
200 | https://wp-yoda.com/author-sitemap.xml
200 | https://wp-yoda.com/notice_category-sitemap.xml
=============
200 | X-Cache: MISS     | Cf-Cache-Status: MISS    | https://wp-yoda.com/
200 | X-Cache: MISS     | Cf-Cache-Status: BYPASS  | https://wp-yoda.com/about-me/
200 | X-Cache: MISS     | Cf-Cache-Status: MISS    | https://wp-yoda.com/adaptacziya-programmnyh-produktov/otlichiya-i18n-i-i10n/
200 | X-Cache: MISS     | Cf-Cache-Status: MISS    | https://wp-yoda.com/bazy-dannyh/klasternye-i-neklasternye-indeksy/
200 | X-Cache: MISS     | Cf-Cache-Status: MISS    | https://wp-yoda.com/category/bazy-dannyh/relyaczionnye-bazy-dannyh/mysql/
Delay 2 sec
200 | X-Cache: MISS     | Cf-Cache-Status: MISS    | https://wp-yoda.com/category/adaptacziya-programmnyh-produktov/
200 | X-Cache: MISS     | Cf-Cache-Status: MISS    | https://wp-yoda.com/category/bash/
200 | X-Cache: MISS     | Cf-Cache-Status: MISS    | https://wp-yoda.com/category/bazy-dannyh/
200 | X-Cache: MISS     | Cf-Cache-Status: MISS    | https://wp-yoda.com/category/bazy-dannyh/relyaczionnye-bazy-dannyh/
200 | X-Cache: HIT 1    | Cf-Cache-Status: MISS    | https://wp-yoda.com/category/macos/
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

