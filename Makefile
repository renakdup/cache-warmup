
php.connect82xdebug:
	docker run --rm -it -v "${PWD}":/usr/src/myapp -w /usr/src/myapp sineverba/php8xc:1.15.0 bash


php.connect83xdebug:
	docker run --rm -it -v "${PWD}":/usr/src/myapp -w /usr/src/myapp sineverba/php8xc:1.18.0 bash


phpstan:
	docker run --rm -it -v "${PWD}":/usr/src/myapp -w /usr/src/myapp shopware/development:8.2-composer-2 sh -c 'composer run phpstan'


##########################
# Tests
##########################
unit.run:
	docker run --rm -it -v "${PWD}":/usr/src/myapp -w /usr/src/myapp shopware/development:8.2-composer-2 sh -c 'composer run phpunit -- --do-not-cache-result'

unit.generate.report.html:
	docker run --rm -it -v "${PWD}":/usr/src/myapp -w /usr/src/myapp sineverba/php8xc:1.18.0 sh -c 'composer run phpunit-report-html'

unit.generate.report.clover:
	docker run --


composer.normilize:
	composer normalize