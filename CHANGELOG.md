# CHANGELOG

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com), and this project adheres to [Semantic Versioning](https://semver.org).

## [Unreleased]
### Added
* [#1089](https://github.com/shlinkio/shlink/issues/1089) Added new `ENABLE_PERIODIC_VISIT_LOCATE` env var to docker image which schedules the `visit:locate` command every hour when provided with value `true`.
* [#1082](https://github.com/shlinkio/shlink/issues/1082) Added support for error correction level on QR codes.

    Now, when calling the `GET /{shorCode}/qr-code` URL, you can pass the `errorCorrection` query param with values `L` for Low, `M` for Medium, `Q` for Quartile or `H` for High.

* [#1080](https://github.com/shlinkio/shlink/issues/1080) Added support to redirect to URLs as soon as the path starts with a valid short code, appending the rest of the path to the redirected long URL.

    With this, if you have the `https://example.com/abc123` short URL redirecting to `https://www.twitter.com`, a visit to `https://example.com/abc123/shlinkio` will take you to `https://www.twitter.com/shlinkio`.

    This behavior needs to be actively opted in, via installer config options or env vars.

* [#943](https://github.com/shlinkio/shlink/issues/943) Added support to define different "not-found" redirects for every domain handled by Shlink.

    Shlink will continue to allow defining the default values via env vars or config, but afterwards, you can use the `domain:redirects` command to define specific values for every single domain.

### Changed
* [#1118](https://github.com/shlinkio/shlink/issues/1118) Increased phpstan required level to 8.

### Deprecated
* *Nothing*

### Removed
* [#1046](https://github.com/shlinkio/shlink/issues/1046) Dropped support for PHP 7.4.

### Fixed
* *Nothing*


## [2.7.1] - 2021-05-30
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#1100](https://github.com/shlinkio/shlink/issues/1100) Fixed Shlink trying to download GeoLite2 db files even when tracking has been disabled.


## [2.7.0] - 2021-05-23
### Added
* [#1044](https://github.com/shlinkio/shlink/issues/1044) Added ability to set names on API keys, which helps to identify them when the list grows.
* [#819](https://github.com/shlinkio/shlink/issues/819) Visits are now always located in real time, even when not using swoole.

    The only side effect is that a GeoLite2 db file is now installed when the docker image starts or during shlink installation or update.

    Also, when using swoole, the file is now updated **after** tracking a visit, which means it will not apply until the next one.

* [#1059](https://github.com/shlinkio/shlink/issues/1059) Added ability to optionally display author API key and its name when listing short URLs from the command line.
* [#1066](https://github.com/shlinkio/shlink/issues/1066) Added support to import short URLs and their visits from another Shlink instance using its API.
* [#898](https://github.com/shlinkio/shlink/issues/898) Improved tracking granularity, allowing to disable visits tracking completely, or just parts of it.

    In order to achieve it, Shlink now supports 4 new tracking-related options, that can be customized via env vars for docker, or via installer:

    * `disable_tracking`: If true, visits will not be tracked at all.
    * `disable_ip_tracking`: If true, visits will be tracked, but neither the IP address, nor the location will be resolved.
    * `disable_referrer_tracking`: If true, the referrer will not be tracked.
    * `disable_ua_tracking`: If true, the user agent will not be tracked.

* [#955](https://github.com/shlinkio/shlink/issues/955) Added new option to set short URLs as crawlable, making them be listed in the robots.txt as Allowed.
* [#900](https://github.com/shlinkio/shlink/issues/900) Shlink now tries to detect if the visit is coming from a potential bot or crawler, and allows to exclude those visits from visits lists if desired.

### Changed
* [#1036](https://github.com/shlinkio/shlink/issues/1036) Updated to `happyr/doctrine-specification` 2.0.
* [#1039](https://github.com/shlinkio/shlink/issues/1039) Updated to `endroid/qr-code` 4.0.
* [#1008](https://github.com/shlinkio/shlink/issues/1008) Ensured all logs are sent to the filesystem while running API tests, which helps debugging the reason for tests to fail.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#1041](https://github.com/shlinkio/shlink/issues/1041) Ensured the default value for the version while building the docker image is `latest`.
* [#1067](https://github.com/shlinkio/shlink/issues/1067) Fixed exception when persisting multiple short URLs in one batch which include the same new tags/domains. This can potentially happen when importing URLs.


## [2.6.2] - 2021-03-12
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#1047](https://github.com/shlinkio/shlink/issues/1047) Fixed error in migrations when doing a fresh installation using PHP8 and MySQL/Mariadb databases.


## [2.6.1] - 2021-02-22
### Added
* *Nothing*

### Changed
* [#1026](https://github.com/shlinkio/shlink/issues/1026) Removed non-inclusive terms from source code.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#1024](https://github.com/shlinkio/shlink/issues/1024) Fixed migration that is incorrectly skipped due to the wrong condition being used to check it.
* [#1031](https://github.com/shlinkio/shlink/issues/1031) Fixed shortening of twitter URLs with URL validation enabled.
* [#1034](https://github.com/shlinkio/shlink/issues/1034) Fixed warning displayed when shlink is stopped while running it with swoole.


## [2.6.0] - 2021-02-13
### Added
* [#856](https://github.com/shlinkio/shlink/issues/856) Added PHP 8.0 support.
* [#941](https://github.com/shlinkio/shlink/issues/941) Added support to provide a title for every short URL.

    The title can also be automatically resolved from the long URL, when no title was explicitly provided, but this option needs to be opted in.

* [#913](https://github.com/shlinkio/shlink/issues/913) Added support to import short URLs from a standard CSV file.

    The file requires the `Long URL` and `Short code` columns, and it also accepts the optional `title`, `domain` and `tags` columns.

* [#1000](https://github.com/shlinkio/shlink/issues/1000) Added support to provide a `margin` query param when generating some URL's QR code.
* [#675](https://github.com/shlinkio/shlink/issues/675) Added ability to track visits to the base URL, invalid short URLs or any other "not found" URL, as known as orphan visits.

    This behavior is enabled by default, but you can opt out via env vars or config options.

    This new orphan visits can be consumed in these ways:

    * The `https://shlink.io/new-orphan-visit` mercure topic, which gets notified when an orphan visit occurs.
    * The `GET /visits/orphan` REST endpoint, which behaves like the short URL visits and tags visits endpoints, but returns only orphan visits.

### Changed
* [#977](https://github.com/shlinkio/shlink/issues/977) Migrated from `laminas/laminas-paginator` to `pagerfanta/core` to handle pagination.
* [#986](https://github.com/shlinkio/shlink/issues/986) Updated official docker image to use PHP 8.
* [#1010](https://github.com/shlinkio/shlink/issues/1010) Increased timeout for database commands to 10 minutes.
* [#874](https://github.com/shlinkio/shlink/issues/874) Changed how dist files are generated. Now there will be two for every supported PHP version, with and without support for swoole.

    The dist files will have been built under the same PHP version they are meant to be run under, ensuring resolved dependencies are the proper ones.

### Deprecated
* [#959](https://github.com/shlinkio/shlink/issues/959) Deprecated all command flags using camelCase format (like `--expirationDate`), adding kebab-case replacements for all of them (like `--expiration-date`).

    All the existing camelCase flags will continue working for now, but will be removed in Shlink 3.0.0

* [#862](https://github.com/shlinkio/shlink/issues/862) Deprecated the endpoint to edit tags for a short URL (`PUT /short-urls/{shortCode}/tags`).

    The short URL edition endpoint (`PATCH /short-urls/{shortCode}`) now supports setting the tags too. Use it instead.

### Removed
* *Nothing*

### Fixed
* [#988](https://github.com/shlinkio/shlink/issues/988) Fixed serving zero-byte static files in apache and apache-compatible web servers.
* [#990](https://github.com/shlinkio/shlink/issues/990) Fixed short URLs not properly composed in REST API endpoints when both custom domain and custom base path are used.
* [#1002](https://github.com/shlinkio/shlink/issues/1002) Fixed weird behavior in which GeoLite2 metadata's `buildEpoch` is parsed as string instead of int.
* [#851](https://github.com/shlinkio/shlink/issues/851) Fixed error when trying to schedule swoole tasks in ARM architectures (like raspberry).


## [2.5.2] - 2021-01-24
### Added
* [#965](https://github.com/shlinkio/shlink/issues/965) Added docs section for Architectural Decision Records, including the one for API key roles.

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#979](https://github.com/shlinkio/shlink/issues/979) Added missing `itemsPerPage` query param to swagger docs for short RULs list.
* [#980](https://github.com/shlinkio/shlink/issues/980) Fixed value used for `Access-Control-Allow-Origin`, that could not work as expected when including an IP address.
* [#947](https://github.com/shlinkio/shlink/issues/947) Fixed incorrect value returned in `Access-Control-Allow-Methods` header, which always contained all methods.


## [2.5.1] - 2021-01-21
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#968](https://github.com/shlinkio/shlink/issues/968) Fixed index error in MariaDB while updating to v2.5.0.
* [#972](https://github.com/shlinkio/shlink/issues/972) Fixed 500 error when calling single-step short URL creation endpoint.


## [2.5.0] - 2021-01-17
### Added
* [#795](https://github.com/shlinkio/shlink/issues/795) and [#882](https://github.com/shlinkio/shlink/issues/882) Added new roles system to API keys.

    API keys can have any combinations of these two roles now, allowing to limit their interactions:

    * Can interact only with short URLs created with that API key.
    * Can interact only with short URLs for a specific domain.

* [#833](https://github.com/shlinkio/shlink/issues/833) Added support to connect through unix socket when using an external MySQL, MariaDB or Postgres database.

    It can be provided during the installation, or as the `DB_UNIX_SOCKET` env var for the docker image.

* [#869](https://github.com/shlinkio/shlink/issues/869) Added support for Mercure Hub 0.10.
* [#896](https://github.com/shlinkio/shlink/issues/896) Added support for unicode characters in custom slugs.
* [#930](https://github.com/shlinkio/shlink/issues/930) Added new `bin/set-option` script that allows changing individual configuration options on existing shlink instances.
* [#877](https://github.com/shlinkio/shlink/issues/877) Improved API tests on CORS, and "refined" middleware handling it.

### Changed
* [#912](https://github.com/shlinkio/shlink/issues/912) Changed error templates to be plain html files, removing the dependency on `league/plates` package.
* [#875](https://github.com/shlinkio/shlink/issues/875) Updated to `mezzio/mezzio-swoole` v3.1.
* [#952](https://github.com/shlinkio/shlink/issues/952) Simplified in-project docs, by keeping only the basics and linking to the websites docs for anything else.

### Deprecated
* [#917](https://github.com/shlinkio/shlink/issues/917) Deprecated `/{shortCode}/qr-code/{size}` URL, in favor of providing the size in the query instead, `/{shortCode}/qr-code?size={size}`.
* [#924](https://github.com/shlinkio/shlink/issues/924) Deprecated mechanism to provide config options to the docker image through volumes. Use the env vars instead as a direct replacement.

### Removed
* *Nothing*

### Fixed
* *Nothing*


## [2.4.2] - 2020-11-22
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#904](https://github.com/shlinkio/shlink/issues/904) Explicitly added missing "Domains" and "Integrations" tags to swagger docs.
* [#901](https://github.com/shlinkio/shlink/issues/901) Ensured domains which are not in use on any short URL are not returned on the list of domains.
* [#899](https://github.com/shlinkio/shlink/issues/899) Avoided filesystem errors produced while downloading geolite DB files on several shlink instances that share the same filesystem.
* [#827](https://github.com/shlinkio/shlink/issues/827) Fixed swoole config getting loaded in config cache if a console command is run before any web execution, when swoole extension is enabled, making subsequent non-swoole web requests fail.


## [2.4.1] - 2020-11-10
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#891](https://github.com/shlinkio/shlink/issues/891) Fixed error when running migrations in postgres due to incorrect return type hint.
* [#846](https://github.com/shlinkio/shlink/issues/846) Fixed base image used for the PHP-FPM dev container.
* [#867](https://github.com/shlinkio/shlink/issues/867) Fixed not-found redirects not using proper status (301 or 302) as configured during installation.


## [2.4.0] - 2020-11-08
### Added
* [#829](https://github.com/shlinkio/shlink/issues/829) Added support for QR codes in SVG format, by passing `?format=svg` to the QR code URL.
* [#820](https://github.com/shlinkio/shlink/issues/820) Added new option to force enabling or disabling URL validation on a per-URL basis.

    Currently, there's a global config that tells if long URLs should be validated (by ensuring they are publicly accessible and return a 2xx status). However, this is either always applied or never applied.

    Now, it is possible to enforce validation or enforce disabling validation when a new short URL is created or edited:

    * On the `POST /short-url` and `PATCH /short-url/{shortCode}` endpoints, you can now pass `validateUrl: true/false` in order to enforce enabling or disabling validation, ignoring the global config. If the value is not provided, the global config is still normally applied.
    * On the `short-url:generate` CLI command, you can pass `--validate-url` or `--no-validate-url` flags, in order to enforce enabling or disabling validation. If none of them is provided, the global config is still normally applied.

* [#838](https://github.com/shlinkio/shlink/issues/838) Added new endpoint and CLI command to list existing domains.

    It returns both default domain and specific domains that were used for some short URLs.

    * REST endpoint: `GET /rest/v2/domains`
    * CLI Command: `domain:list`

* [#832](https://github.com/shlinkio/shlink/issues/832) Added support to customize the port in which the docker image listens by using the `PORT` env var or the `port` config option.

* [#860](https://github.com/shlinkio/shlink/issues/860) Added support to import links from bit.ly.

    Run the command `short-urls:import bitly` and introduce requested information in order to import all your links.

    Other sources will be supported in future releases.

### Changed
* [#836](https://github.com/shlinkio/shlink/issues/836) Added support for the `<field>-<dir>` notation while determining how to order the short URLs list, as in `?orderBy=shortCode-DESC`. This effectively deprecates the array notation (`?orderBy[shortCode]=DESC`), that will be removed in Shlink 3.0.0
* [#782](https://github.com/shlinkio/shlink/issues/782) Added code coverage to API tests.
* [#858](https://github.com/shlinkio/shlink/issues/858) Updated to latest infection version. Updated docker images to PHP 7.4.11 and swoole 4.5.5
* [#887](https://github.com/shlinkio/shlink/pull/887) Started tracking the API key used to create short URLs, in order to allow restrictions in future releases.

### Deprecated
* [#883](https://github.com/shlinkio/shlink/issues/883) Deprecated `POST /tags` endpoint and `tag:create` command, as tags are created automatically while creating short URLs.

### Removed
* *Nothing*

### Fixed
* [#837](https://github.com/shlinkio/shlink/issues/837) Drastically improved performance when creating a new shortUrl and providing `findIfExists = true`.
* [#878](https://github.com/shlinkio/shlink/issues/878) Added missing `gmp` extension to the official docker image.


## [2.3.0] - 2020-08-09
### Added
* [#746](https://github.com/shlinkio/shlink/issues/746) Allowed to configure the kind of redirect you want to use for your short URLs. You can either set:

    * `302` redirects: Default behavior. Visitors always hit the server.
    * `301` redirects: Better for SEO. Visitors hit the server the first time and then cache the redirect.

    When selecting 301 redirects, you can also configure the time redirects are cached, to mitigate deviations in stats.

* [#734](https://github.com/shlinkio/shlink/issues/734) Added support to redirect to deeplinks and other links with schemas different from `http` and `https`.
* [#709](https://github.com/shlinkio/shlink/issues/709) Added multi-architecture builds for the docker image.

* [#707](https://github.com/shlinkio/shlink/issues/707) Added `--all` flag to `short-urls:list` command, which will print all existing URLs in one go, with no pagination.

    It has one limitation, though. Because of the way the CLI tooling works, all rows in the table must be loaded in memory. If the amount of URLs is too high, the command may fail due to too much memory usage.

### Changed
* [#508](https://github.com/shlinkio/shlink/issues/508) Added mutation checks to database tests.
* [#790](https://github.com/shlinkio/shlink/issues/790) Updated to doctrine/migrations v3.
* [#798](https://github.com/shlinkio/shlink/issues/798) Updated to guzzlehttp/guzzle v7.
* [#822](https://github.com/shlinkio/shlink/issues/822) Updated docker image to use PHP 7.4.9 with Alpine 3.12 and swoole 4.5.2.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* *Nothing*


## [2.2.2] - 2020-06-08
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#769](https://github.com/shlinkio/shlink/issues/769) Fixed custom slugs not allowing valid URL characters, like `.`, `_` or `~`.
* [#781](https://github.com/shlinkio/shlink/issues/781) Fixed memory leak when loading visits for a tag which is used for big amounts of short URLs.


## [2.2.1] - 2020-05-11
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#764](https://github.com/shlinkio/shlink/issues/764) Fixed error when trying to match an existing short URL which does not have `validSince` and/or `validUntil`, but you are providing either one of them for the new one.


## [2.2.0] - 2020-05-09
### Added
* [#712](https://github.com/shlinkio/shlink/issues/712) Added support to integrate Shlink with a [mercure hub](https://mercure.rocks/) server.

    Thanks to that, Shlink will be able to publish events that can be consumed in real time.

    For now, two topics (events) are published, when new visits occur. Both include a payload with the visit and the shortUrl:

        * A visit occurs on any short URL: `https://shlink.io/new-visit`.
        * A visit occurs on short URLs with a specific short code: `https://shlink.io/new-visit/{shortCode}`.

    The updates are only published when serving Shlink with swoole.

    Also, Shlink exposes a new endpoint `GET /rest/v2/mercure-info`, which returns the public URL of the mercure hub, and a valid JWT that can be used to subscribe to updates.

* [#673](https://github.com/shlinkio/shlink/issues/673) Added new `[GET /visits]` rest endpoint which returns basic visits stats.
* [#674](https://github.com/shlinkio/shlink/issues/674) Added new `[GET /tags/{tag}/visits]` rest endpoint which returns visits by tag.

    It works in the same way as the `[GET /short-urls/{shortCode}/visits]` one, returning the same response payload, and supporting the same query params, but the response is the list of visits in all short URLs which have provided tag.

* [#672](https://github.com/shlinkio/shlink/issues/672) Enhanced `[GET /tags]` rest endpoint so that it is possible to get basic stats info for every tag.

    Now, if the `withStats=true` query param is provided, the response payload will include a new `stats` property which is a list with the amount of short URLs and visits for every tag.

    Also, the `tag:list` CLI command has been changed and it always behaves like this.

* [#640](https://github.com/shlinkio/shlink/issues/640) Allowed to optionally disable visitors' IP address anonymization. This will make Shlink no longer be GDPR-compliant, but it's OK if you only plan to share your URLs in countries without this regulation.

### Changed
* [#692](https://github.com/shlinkio/shlink/issues/692) Drastically improved performance when loading visits. Specially noticeable when loading big result sets.
* [#657](https://github.com/shlinkio/shlink/issues/657) Updated how DB tests are run in travis by using docker containers which allow all engines to be covered.
* [#751](https://github.com/shlinkio/shlink/issues/751) Updated PHP and swoole versions used in docker image, and removed mssql-tools, as they are not needed.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#729](https://github.com/shlinkio/shlink/issues/729) Fixed weird error when fetching multiple visits result sets concurrently using mariadb or mysql.
* [#735](https://github.com/shlinkio/shlink/issues/735) Fixed error when cleaning metadata cache during installation when APCu is enabled.
* [#677](https://github.com/shlinkio/shlink/issues/677) Fixed `/health` endpoint returning `503` fail responses when the database connection has expired.
* [#732](https://github.com/shlinkio/shlink/issues/732) Fixed wrong client IP in access logs when serving app with swoole behind load balancer.


## [2.1.4] - 2020-04-30
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#742](https://github.com/shlinkio/shlink/issues/742) Allowed a custom GeoLite2 license key to be provided, in order to avoid download limits.


## [2.1.3] - 2020-04-09
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#712](https://github.com/shlinkio/shlink/issues/712) Fixed app set-up not clearing entities metadata cache.
* [#711](https://github.com/shlinkio/shlink/issues/711) Fixed `HEAD` requests returning a duplicated `Content-Length` header.
* [#716](https://github.com/shlinkio/shlink/issues/716) Fixed Twitter not properly displaying preview for final long URL.
* [#717](https://github.com/shlinkio/shlink/issues/717) Fixed DB connection expiring on task workers when using swoole.
* [#705](https://github.com/shlinkio/shlink/issues/705) Fixed how the short URL domain is inferred when generating QR codes, making sure the configured domain is respected even if the request is performed using a different one, and only when a custom domain is used, then that one is used instead.


## [2.1.2] - 2020-03-29
### Added
* *Nothing*

### Changed
* [#696](https://github.com/shlinkio/shlink/issues/696) Updated to infection v0.16.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#700](https://github.com/shlinkio/shlink/issues/700) Fixed migration not working with postgres.
* [#690](https://github.com/shlinkio/shlink/issues/690) Fixed tags being incorrectly sluggified when filtering short URL lists, making results not be the expected.


## [2.1.1] - 2020-03-28
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#697](https://github.com/shlinkio/shlink/issues/697) Recovered `.htaccess` file that was unintentionally removed in v2.1.0, making Shlink unusable with Apache.


## [2.1.0] - 2020-03-28
### Added
* [#626](https://github.com/shlinkio/shlink/issues/626) Added support for Microsoft SQL Server.
* [#556](https://github.com/shlinkio/shlink/issues/556) Short code lengths can now be customized, both globally and on a per-short URL basis.
* [#541](https://github.com/shlinkio/shlink/issues/541) Added a request ID that is returned on `X-Request-Id` header, can be provided from outside and is set in log entries.
* [#642](https://github.com/shlinkio/shlink/issues/642) IP geolocation is now performed over the non-anonymized IP address when using swoole.
* [#521](https://github.com/shlinkio/shlink/issues/521) The long URL for any existing short URL can now be edited using the `PATCH /short-urls/{shortCode}` endpoint.

### Changed
* [#656](https://github.com/shlinkio/shlink/issues/656) Updated to PHPUnit 9.
* [#641](https://github.com/shlinkio/shlink/issues/641) Added two new flags to the `visit:locate` command, `--retry` and `--all`.

    * When `--retry` is provided, it will try to re-locate visits which IP address was originally considered not found, in case it was a temporal issue.
    * When `--all` is provided together with `--retry`, it will try to re-locate all existing visits. A warning and confirmation are displayed, as this can have side effects.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#665](https://github.com/shlinkio/shlink/issues/665) Fixed `base_url_redirect_to` simplified config option not being properly parsed.
* [#663](https://github.com/shlinkio/shlink/issues/663) Fixed Shlink allowing short URLs to be created with an empty custom slug.
* [#678](https://github.com/shlinkio/shlink/issues/678) Fixed `db` commands not running in a non-interactive way.


## [2.0.5] - 2020-02-09
### Added
* [#651](https://github.com/shlinkio/shlink/issues/651) Documented how Shlink behaves when using multiple domains.

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#648](https://github.com/shlinkio/shlink/issues/648) Ensured any user can write in log files, in case shlink is run by several system users.
* [#650](https://github.com/shlinkio/shlink/issues/650) Ensured default domain is ignored when trying to create a short URL.


## [2.0.4] - 2020-02-02
### Added
* *Nothing*

### Changed
* [#577](https://github.com/shlinkio/shlink/issues/577) Wrapped params used to customize short URL lists into a DTO with implicit validation.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#620](https://github.com/shlinkio/shlink/issues/620) Ensured "controlled" errors (like validation errors and such) won't be logged with error level, preventing logs to be polluted.
* [#637](https://github.com/shlinkio/shlink/issues/637) Fixed several work flows in which short URLs with domain are handled form the API.
* [#644](https://github.com/shlinkio/shlink/issues/644) Fixed visits to short URL on non-default domain being linked to the URL on default domain with the same short code.
* [#643](https://github.com/shlinkio/shlink/issues/643) Fixed searching on short URL lists not taking into consideration the domain name.


## [2.0.3] - 2020-01-27
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#624](https://github.com/shlinkio/shlink/issues/624) Fixed order in which headers for remote IP detection are inspected.
* [#623](https://github.com/shlinkio/shlink/issues/623) Fixed short URLs metadata being impossible to reset.
* [#628](https://github.com/shlinkio/shlink/issues/628) Fixed `GET /short-urls/{shortCode}` REST endpoint returning a 404 for short URLs which are not enabled.
* [#621](https://github.com/shlinkio/shlink/issues/621) Fixed permission denied error when updating same GeoLite file version more than once.


## [2.0.2] - 2020-01-12
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#614](https://github.com/shlinkio/shlink/issues/614) Fixed `OPTIONS` requests including the `Origin` header not always returning an empty body with status 2xx.
* [#615](https://github.com/shlinkio/shlink/issues/615) Fixed query args with no value being lost from the long URL when users are redirected.


## [2.0.1] - 2020-01-10
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#607](https://github.com/shlinkio/shlink/issues/607) Added missing info on UPGRADE.md doc.
* [#610](https://github.com/shlinkio/shlink/issues/610) Fixed use of hardcoded quotes on a database migration which makes it fail on postgres.
* [#605](https://github.com/shlinkio/shlink/issues/605) Fixed crashes occurring when migrating from old Shlink versions with nullable DB columns that are assigned to non-nullable entity typed props.


## [2.0.0] - 2020-01-08
### Added
* [#429](https://github.com/shlinkio/shlink/issues/429) Added support for PHP 7.4
* [#529](https://github.com/shlinkio/shlink/issues/529) Created an UPGRADING.md file explaining how to upgrade from v1.x to v2.x
* [#594](https://github.com/shlinkio/shlink/issues/594) Updated external shlink packages, including installer v4.0, which adds the option to ask for the redis cluster config.

### Changed
* [#592](https://github.com/shlinkio/shlink/issues/592) Updated coding styles to use [shlinkio/php-coding-standard](https://github.com/shlinkio/php-coding-standard) v2.1.0.
* [#530](https://github.com/shlinkio/shlink/issues/530) Migrated project from deprecated `zendframework` components to the new `laminas` and `mezzio` ones.

### Deprecated
* *Nothing*

### Removed
* [#429](https://github.com/shlinkio/shlink/issues/429) Dropped support for PHP 7.2 and 7.3

* [#229](https://github.com/shlinkio/shlink/issues/229) Remove everything which was deprecated, including:

    * Preview generation feature completely removed.
    * Authentication against REST API using JWT is no longer supported.

    See [UPGRADE](UPGRADE.md) doc in order to get details on how to migrate to this version.

### Fixed
* [#600](https://github.com/shlinkio/shlink/issues/600) Fixed health action so that it works with and without version in the path.


## [1.21.1] - 2020-01-02
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#596](https://github.com/shlinkio/shlink/issues/596) Fixed error when trying to download GeoLite2 database due to changes on how to get the database files.


## [1.21.0] - 2019-12-29
### Added
* [#118](https://github.com/shlinkio/shlink/issues/118) API errors now implement the [problem details](https://tools.ietf.org/html/rfc7807) standard.

    In order to make it backwards compatible, two things have been done:

    * Both the old `error` and `message` properties have been kept on error response, containing the same values as the `type` and `detail` properties respectively.
    * The API `v2` has been enabled. If an error occurs when calling the API with this version, the `error` and `message` properties will not be returned.

    > After Shlink v2 is released, both API versions will behave like API v2.

* [#575](https://github.com/shlinkio/shlink/issues/575) Added support to filter short URL lists by date ranges.

    * The `GET /short-urls` endpoint now accepts the `startDate` and `endDate` query params.
    * The `short-urls:list` command now allows `--startDate` and `--endDate` flags to be optionally provided.

* [#338](https://github.com/shlinkio/shlink/issues/338) Added support to asynchronously notify external services via webhook, only when shlink is served with swoole.

    Configured webhooks will receive a POST request every time a URL receives a visit, including information about the short URL and the visit.

    The payload will look like this:

    ```json
    {
      "shortUrl": {},
      "visit": {}
    }
    ```

    > The `shortUrl` and `visit` props have the same shape as it is defined in the [API spec](https://api-spec.shlink.io).

### Changed
* [#492](https://github.com/shlinkio/shlink/issues/492) Updated to monolog 2, together with other dependencies, like Symfony 5 and infection-php.
* [#527](https://github.com/shlinkio/shlink/issues/527) Increased minimum required mutation score for unit tests to 80%.
* [#557](https://github.com/shlinkio/shlink/issues/557) Added a few php.ini configs for development and production docker images.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#570](https://github.com/shlinkio/shlink/issues/570) Fixed shlink version generated for docker images when building from `develop` branch.


## [1.20.3] - 2019-12-23
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#585](https://github.com/shlinkio/shlink/issues/585) Fixed `PHP Fatal error: Uncaught Error: Class 'Shlinkio\Shlink\LocalLockFactory' not found` happening when running some CLI commands.


## [1.20.2] - 2019-12-06
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#561](https://github.com/shlinkio/shlink/issues/561) Fixed `db:migrate` command failing because yaml extension is not installed, which makes config file not to be readable.
* [#562](https://github.com/shlinkio/shlink/issues/562) Fixed internal server error being returned when renaming a tag to another tag's name. Now a meaningful API error with status 409 is returned.
* [#555](https://github.com/shlinkio/shlink/issues/555) Fixed internal server error being returned when invalid dates are provided for new short URLs. Now a 400 is returned, as intended.


## [1.20.1] - 2019-11-17
### Added
* [#519](https://github.com/shlinkio/shlink/issues/519) Documented how to customize web workers and task workers for the docker image.

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#512](https://github.com/shlinkio/shlink/issues/512) Fixed query params not being properly forwarded from short URL to long one.
* [#540](https://github.com/shlinkio/shlink/issues/540) Fixed errors thrown when creating short URLs if the original URL has an internationalized domain name and URL validation is enabled.
* [#528](https://github.com/shlinkio/shlink/issues/528) Ensured `db:create` and `db:migrate` commands do not silently fail when run as part of `install` or `update`.
* [#518](https://github.com/shlinkio/shlink/issues/518) Fixed service which updates Geolite db file to use a local lock instead of a shared one, since every shlink instance holds its own db instance.


## [1.20.0] - 2019-11-02
### Added
* [#491](https://github.com/shlinkio/shlink/issues/491) Added improved short code generation logic.

    Now, short codes are truly random, which removes the guessability factor existing in previous versions.

    Generated short codes have 5 characters, and shlink makes sure they keep unique, while making it backwards-compatible.

* [#418](https://github.com/shlinkio/shlink/issues/418) and [#419](https://github.com/shlinkio/shlink/issues/419) Added support to redirect any 404 error to a custom URL.

    It was already possible to configure this but only for invalid short URLs. Shlink now also support configuring redirects for the base URL and any other kind of "not found" error.

    The three URLs can be different, and it is already possible to pass them to the docker image via configuration or env vars.

    The installer also asks for these two new configuration options.

* [#497](https://github.com/shlinkio/shlink/issues/497) Officially added support for MariaDB.

### Changed
* [#458](https://github.com/shlinkio/shlink/issues/458) Updated coding styles to use [shlinkio/php-coding-standard](https://github.com/shlinkio/php-coding-standard) v2.0.0.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#507](https://github.com/shlinkio/shlink/issues/507) Fixed error with too long original URLs by increasing size to the maximum value (2048) based on [the standard](https://stackoverflow.com/a/417184).
* [#502](https://github.com/shlinkio/shlink/issues/502) Fixed error when providing the port as part of the domain on short URLs.
* [#509](https://github.com/shlinkio/shlink/issues/509) Fixed error when trying to generate a QR code for a short URL which uses a custom domain.
* [#522](https://github.com/shlinkio/shlink/issues/522) Highly mitigated errors thrown when lots of short URLs are created concurrently including new and existing tags.


## [1.19.0] - 2019-10-05
### Added
* [#482](https://github.com/shlinkio/shlink/issues/482) Added support to serve shlink under a sub path.

    The `router.base_path` config option can be defined now to set the base path from which shlink is served.

    ```php
    return [
        'router' => [
            'base_path' => '/foo/bar',
        ],
    ];
    ```

    This option will also be available on shlink-installer 1.3.0, so the installer will ask for it. It can also be provided for the docker image as the `BASE_PATH` env var.

* [#479](https://github.com/shlinkio/shlink/issues/479) Added preliminary support for multiple domains.

    Endpoints and commands which create short URLs support providing the `domain` now (via query param or CLI flag). If not provided, the short URLs will still be "attached" to the default domain.

    Custom slugs can be created on multiple domains, allowing to share links like `https://doma.in/my-compaign` and `https://example.com/my-campaign`, under the same shlink instance.

    When resolving a short URL to redirect end users, the following rules are applied:

    * If the domain used for the request plus the short code/slug are found, the user is redirected to that long URL and the visit is tracked.
    * If the domain is not known but the short code/slug is defined for default domain, the user is redirected there and the visit is tracked.
    * In any other case, no redirection happens and no visit is tracked (if a fall back redirection is configured for not-found URLs, it will still happen).

### Changed
* [#486](https://github.com/shlinkio/shlink/issues/486) Updated to [shlink-installer](https://github.com/shlinkio/shlink-installer) v2, which supports asking for base path in which shlink is served.

### Deprecated
* *Nothing*

### Removed
* [#435](https://github.com/shlinkio/shlink/issues/435) Removed translations for error pages. All error pages are in english now.

### Fixed
* *Nothing*


## [1.18.1] - 2019-08-24
### Added
* *Nothing*

### Changed
* [#450](https://github.com/shlinkio/shlink/issues/450) Added PHP 7.4 to the build matrix, as an allowed-to-fail env.
* [#441](https://github.com/shlinkio/shlink/issues/441) and [#443](https://github.com/shlinkio/shlink/issues/443) Split some logic into independent modules.
* [#451](https://github.com/shlinkio/shlink/issues/451) Updated to infection 0.13.
* [#467](https://github.com/shlinkio/shlink/issues/467) Moved docker image config to main Shlink repo.

### Deprecated
* [#428](https://github.com/shlinkio/shlink/issues/428) Deprecated preview-generation feature. It will keep working but it will be removed in Shlink v2.0.0

### Removed
* [#468](https://github.com/shlinkio/shlink/issues/468) Removed APCu extension from docker image.

### Fixed
* [#449](https://github.com/shlinkio/shlink/issues/449) Fixed error when trying to save too big referrers on PostgreSQL.


## [1.18.0] - 2019-08-08
### Added
* [#411](https://github.com/shlinkio/shlink/issues/411) Added new `meta` property on the `ShortUrl` REST API model.

    These endpoints are affected and include the new property when suitable:

    * `GET /short-urls` - List short URLs.
    * `GET /short-urls/shorten` - Create a short URL (for integrations).
    * `GET /short-urls/{shortCode}` - Get one short URL.
    * `POST /short-urls` - Create short URL.

    The property includes the values `validSince`, `validUntil` and `maxVisits` in a single object. All of them are nullable.

    ```json
    {
      "validSince": "2016-01-01T00:00:00+02:00",
      "validUntil": null,
      "maxVisits": 100
    }
    ```

* [#285](https://github.com/shlinkio/shlink/issues/285) Visit location resolution is now done asynchronously but in real time thanks to swoole task management.

    Now, when a short URL is visited, a task is enqueued to locate it. The user is immediately redirected to the long URL, and in the background, the visit is located, making stats to be available a couple of seconds after the visit without the requirement of cronjobs being run constantly.

    Sadly, this feature is not enabled when serving shlink via apache/nginx, where you should still rely on cronjobs.

* [#384](https://github.com/shlinkio/shlink/issues/384) Improved how remote IP addresses are detected.

    This new set of headers is now also inspected looking for the IP address:

    * CF-Connecting-IP
    * True-Client-IP
    * X-Real-IP

* [#440](https://github.com/shlinkio/shlink/pull/440) Created `db:create` command, which improves how the shlink database is created, with these benefits:

    * It sets up a lock which prevents the command to be run concurrently.
    * It checks of the database does not exist, and creates it in that case.
    * It checks if the database tables already exist, exiting gracefully in that case.

* [#442](https://github.com/shlinkio/shlink/pull/442) Created `db:migrate` command, which improves doctrine's migrations command by generating a lock, preventing it to be run concurrently.

### Changed
* [#430](https://github.com/shlinkio/shlink/issues/430) Updated to [shlinkio/php-coding-standard](https://github.com/shlinkio/php-coding-standard) 1.2.2
* [#305](https://github.com/shlinkio/shlink/issues/305) Implemented changes which will allow Shlink to be truly clusterizable.
* [#262](https://github.com/shlinkio/shlink/issues/262) Increased mutation score to 75%.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#416](https://github.com/shlinkio/shlink/issues/416) Fixed error thrown when trying to locate visits after the GeoLite2 DB is downloaded for the first time.
* [#424](https://github.com/shlinkio/shlink/issues/424) Updated wkhtmltoimage to version 0.12.5
* [#427](https://github.com/shlinkio/shlink/issues/427) and [#434](https://github.com/shlinkio/shlink/issues/434) Fixed shlink being unusable after a database error on swoole contexts.


## [1.17.0] - 2019-05-13
### Added
* [#377](https://github.com/shlinkio/shlink/issues/377) Updated `visit:locate` command (formerly `visit:process`) to automatically update the GeoLite2 database if it is too old or it does not exist.

    This simplifies processing visits in a container-based infrastructure, since a fresh container is capable of getting an updated version of the file by itself.

    It also removes the need of asynchronously and programmatically updating the file, which deprecates the `visit:update-db` command.

* [#373](https://github.com/shlinkio/shlink/issues/373) Added support for a simplified config. Specially useful to use with the docker container.

### Changed
* [#56](https://github.com/shlinkio/shlink/issues/56) Simplified supported cache, requiring APCu always.

### Deprecated
* [#406](https://github.com/shlinkio/shlink/issues/406) Deprecated `PUT /short-urls/{shortCode}` REST endpoint in favor of `PATCH /short-urls/{shortCode}`.

### Removed
* [#385](https://github.com/shlinkio/shlink/issues/385) Dropped support for PHP 7.1
* [#379](https://github.com/shlinkio/shlink/issues/379) Removed copyright from error templates.

### Fixed
* *Nothing*


## [1.16.3] - 2019-03-30
### Added
* *Nothing*

### Changed
* [#153](https://github.com/shlinkio/shlink/issues/153) Updated to [doctrine/migrations](https://github.com/doctrine/migrations) version 2.0.0
* [#376](https://github.com/shlinkio/shlink/issues/376) Allowed `visit:update-db` command to not return an error exit code even if download fails, by passing the `-i` flag.
* [#341](https://github.com/shlinkio/shlink/issues/341) Improved database tests so that they are executed against all supported database engines.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#382](https://github.com/shlinkio/shlink/issues/382) Fixed existing short URLs not properly checked when providing the `findIfExists` flag.


## [1.16.2] - 2019-03-05
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#368](https://github.com/shlinkio/shlink/issues/368) Fixed error produced when running a `SELECT COUNT(...)` with `ORDER BY` in PostgreSQL databases.


## [1.16.1] - 2019-02-26
### Added
* *Nothing*

### Changed
* [#363](https://github.com/shlinkio/shlink/issues/363) Updated to `shlinkio/php-coding-standard` version 1.1.0

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#362](https://github.com/shlinkio/shlink/issues/362) Fixed all visits without an IP address being processed every time the `visit:process` command is executed.


## [1.16.0] - 2019-02-23
### Added
* [#304](https://github.com/shlinkio/shlink/issues/304) Added health endpoint to check healthiness of the service. Useful in container-based infrastructures.

    Call [GET /rest/health] in order to get a response like this:

    ```http
    HTTP/1.1 200 OK
    Content-Type: application/health+json
    Content-Length: 681

    {
      "status": "pass",
      "version": "1.16.0",
      "links": {
        "about": "https://shlink.io",
        "project": "https://github.com/shlinkio/shlink"
      }
    }
    ```

    The status code can be `200 OK` in case of success or `503 Service Unavailable` in case of error, while the `status` property will be one of `pass` or `fail`, as defined in the [Health check RFC](https://inadarei.github.io/rfc-healthcheck/).

* [#279](https://github.com/shlinkio/shlink/issues/279) Added new `findIfExists` flag to the `[POST /short-url]` REST endpoint and the `short-urls:generate` CLI command. It can be used to return existing short URLs when found, instead of creating new ones.

    Thanks to this flag you won't need to remember if you created a short URL for a long one. It will just create it if needed or return the existing one if possible.

    The behavior might be a little bit counterintuitive when combined with other params. This is how the endpoint behaves when providing this new flag:

    * Only the long URL is provided: It will return the newest match or create a new short URL if none is found.
    * Long url and custom slug are provided: It will return the short URL when both params match, return an error when the slug is in use for another long URL, or create a new short URL otherwise.
    * Any of the above but including other params (tags, validSince, validUntil, maxVisits): It will behave the same as the previous two cases, but it will try to exactly match existing results using all the params. If any of them does not match, it will try to create a new short URL.

* [#336](https://github.com/shlinkio/shlink/issues/336) Added an API test suite which performs API calls to an actual instance of the web service.

### Changed
* [#342](https://github.com/shlinkio/shlink/issues/342) The installer no longer asks for a charset to be provided, and instead, it shuffles the base62 charset.
* [#320](https://github.com/shlinkio/shlink/issues/320) Replaced query builder by plain DQL for all queries which do not need to be dynamically generated.
* [#330](https://github.com/shlinkio/shlink/issues/330) No longer allow failures on PHP 7.3 envs during project CI build.
* [#335](https://github.com/shlinkio/shlink/issues/335) Renamed functional test suite to database test suite, since that better describes what it actually does.
* [#346](https://github.com/shlinkio/shlink/issues/346) Extracted installer as an independent tool.
* [#261](https://github.com/shlinkio/shlink/issues/261) Increased mutation score to 70%.

### Deprecated
* [#351](https://github.com/shlinkio/shlink/issues/351) Deprecated `config:generate-charset` and `config:generate-secret` CLI commands.

### Removed
* *Nothing*

### Fixed
* [#317](https://github.com/shlinkio/shlink/issues/317) Fixed error while trying to generate previews because of global config file being deleted by mistake by build script.
* [#307](https://github.com/shlinkio/shlink/issues/307) Fixed memory leak while trying to process huge amounts of visits due to the query not being properly paginated.


## [1.15.1] - 2018-12-16
### Added
* [#162](https://github.com/shlinkio/shlink/issues/162) Added non-rest endpoints to swagger definition.

### Changed
* [#312](https://github.com/shlinkio/shlink/issues/312) Now all config files both in `php` and `json` format are loaded from `config/params` folder, easing users to provided customizations to docker image.
* [#226](https://github.com/shlinkio/shlink/issues/226) Updated how table are rendered in CLI commands, making use of new features in Symfony 4.2.
* [#321](https://github.com/shlinkio/shlink/issues/321) Extracted entities mappings from entities to external config files.
* [#308](https://github.com/shlinkio/shlink/issues/308) Automated docker image building.

### Deprecated
* *Nothing*

### Removed
* [#301](https://github.com/shlinkio/shlink/issues/301) Removed custom `AccessLogFactory` in favor of the implementation included in [zendframework/zend-expressive-swoole](https://github.com/zendframework/zend-expressive-swoole) v2.2.0

### Fixed
* [#309](https://github.com/shlinkio/shlink/issues/309) Added missing favicon to prevent 404 errors logged when an error page is loaded in a browser.
* [#310](https://github.com/shlinkio/shlink/issues/310) Fixed execution context not being properly detected, making `CloseDbConnectionMiddlware` to be always piped. Now the check is not even made, which simplifies everything.


## [1.15.0] - 2018-12-02
### Added
* [#208](https://github.com/shlinkio/shlink/issues/208) Added initial support to run shlink using [swoole](https://www.swoole.co.uk/), a non-blocking IO server which improves the performance of shlink from 4 to 10 times.

    Run shlink with `./vendor/bin/zend-expressive-swoole start` to start-up the service, which will be exposed in port `8080`.

    Adding the `-d` flag, it will be started as a background service. Then you can use the `./vendor/bin/zend-expressive-swoole stop` command in order to stop it.

* [#266](https://github.com/shlinkio/shlink/issues/266) Added pagination to `GET /short-urls/{shortCode}/visits` endpoint.

    In order to make it backwards compatible, it keeps returning all visits by default, but it now allows to provide the `page` and `itemsPerPage` query parameters in order to configure the number of items to get.

### Changed
* [#267](https://github.com/shlinkio/shlink/issues/267) API responses and the CLI interface is no longer translated and uses english always. Only not found error templates are still translated.
* [#289](https://github.com/shlinkio/shlink/issues/289) Extracted coding standard rules to a separated package.
* [#273](https://github.com/shlinkio/shlink/issues/273) Improved code coverage in repository classes.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#278](https://github.com/shlinkio/shlink/pull/278) Added missing `X-Api-Key` header to the list of valid cross domain headers.
* [#295](https://github.com/shlinkio/shlink/pull/295) Fixed custom slugs so that they are case sensitive and do not try to lowercase provided values.


## [1.14.1] - 2018-11-17
### Added
* *Nothing*

### Changed
* [#260](https://github.com/shlinkio/shlink/issues/260) Increased mutation score to 65%.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#271](https://github.com/shlinkio/shlink/issues/271) Fixed memory leak produced when processing high amounts of visits at the same time.
* [#272](https://github.com/shlinkio/shlink/issues/272) Fixed errors produced when trying to process visits multiple times in parallel, by using a lock which ensures only one instance is run at a time.


## [1.14.0] - 2018-11-16
### Added
* [#236](https://github.com/shlinkio/shlink/issues/236) Added option to define a redirection to a custom URL when a user hits an invalid short URL.

    It only affects URLs matched as "short URL" where the short code is invalid, not any 404 that happens in the app. For example, a request to the path `/foo/bar` will keep returning a 404.

    This new option will be asked by the installer both for new shlink installations and for any previous shlink version which is updated.

* [#189](https://github.com/shlinkio/shlink/issues/189) and [#240](https://github.com/shlinkio/shlink/issues/240) Added new [GeoLite2](https://dev.maxmind.com/geoip/geoip2/geolite2/)-based geolocation service which is faster and more reliable than previous one.

    It does not have API limit problems, since it uses a local database file.

    Previous service is still used as a fallback in case GeoLite DB does not contain any IP address.

### Changed
* [#241](https://github.com/shlinkio/shlink/issues/241) Fixed columns in `visit_locations` table, to be snake_case instead of camelCase.
* [#228](https://github.com/shlinkio/shlink/issues/228) Updated how exceptions are serialized into logs, by using monlog's `PsrLogMessageProcessor`.
* [#225](https://github.com/shlinkio/shlink/issues/225) Performance and maintainability slightly improved by enforcing via code sniffer that all global namespace classes, functions and constants are explicitly imported.
* [#196](https://github.com/shlinkio/shlink/issues/196) Reduced anemic model in entities, defining more expressive public APIs instead.
* [#249](https://github.com/shlinkio/shlink/issues/249) Added [functional-php](https://github.com/lstrojny/functional-php) to ease collections handling.
* [#253](https://github.com/shlinkio/shlink/issues/253) Increased `user_agent` column length in `visits` table to 512.
* [#256](https://github.com/shlinkio/shlink/issues/256) Updated to Infection v0.11.
* [#202](https://github.com/shlinkio/shlink/issues/202) Added missing response examples to OpenAPI docs.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#223](https://github.com/shlinkio/shlink/issues/223) Fixed PHPStan errors produced with symfony/console 4.1.5


## [1.13.2] - 2018-10-18
### Added
* [#233](https://github.com/shlinkio/shlink/issues/233) Added PHP 7.3 to build matrix allowing its failure.

### Changed
* [#235](https://github.com/shlinkio/shlink/issues/235) Improved update instructions (thanks to [tivyhosting](https://github.com/tivyhosting)).

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#237](https://github.com/shlinkio/shlink/issues/233) Solved errors when trying to geo-locate `null` IP addresses.

    Also improved how visitor IP addresses are discovered, thanks to [akrabat/ip-address-middleware](https://github.com/akrabat/ip-address-middleware) package.


## [1.13.1] - 2018-10-16
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#231](https://github.com/shlinkio/shlink/issues/197) Fixed error when processing visits.


## [1.13.0] - 2018-10-06
### Added
* [#197](https://github.com/shlinkio/shlink/issues/197) Added [cakephp/chronos](https://book.cakephp.org/3.0/en/chronos.html) library for date manipulations.
* [#214](https://github.com/shlinkio/shlink/issues/214) Improved build script, which allows builds to be done without "jumping" outside the project directory, and generates smaller dist files.

    It also allows automating the dist file generation in travis-ci builds.

* [#207](https://github.com/shlinkio/shlink/issues/207) Added two new config options which are asked during installation process. The config options already existed in previous shlink version, but you had to manually set their values.

    These are the new options:

    * Visits threshold to allow short URLs to be deleted.
    * Check the visits threshold when trying to delete a short URL via REST API.

### Changed
* [#211](https://github.com/shlinkio/shlink/issues/211) Extracted installer to its own module, which will simplify moving it to a separated package in the future.
* [#200](https://github.com/shlinkio/shlink/issues/200) and [#201](https://github.com/shlinkio/shlink/issues/201) Renamed REST Action classes and CLI Command classes to use the concept of `ShortUrl` instead of the concept of `ShortCode` when referring to the entity, and left the `short code` concept to the identifier which is used as a unique code for a specific `Short URL`.
* [#181](https://github.com/shlinkio/shlink/issues/181) When importing the configuration from a previous shlink installation, it no longer asks to import every block. Instead, it is capable of detecting only new config options introduced in the new version, and ask only for those.

    If no new options are found and you have selected to import config, no further questions will be asked and shlink will just import the old config.

### Deprecated
* [#205](https://github.com/shlinkio/shlink/issues/205) Deprecated `[POST /authenticate]` endpoint, and allowed any API request to be automatically authenticated using the `X-Api-Key` header with a valid API key.

    This effectively deprecates the `Authorization: Bearer <JWT>` authentication form, but it will keep working.

* As of [#200](https://github.com/shlinkio/shlink/issues/200) and [#201](https://github.com/shlinkio/shlink/issues/201) REST urls have changed from `/short-codes/...` to `/short-urls/...`, and the command namespaces have changed from `short-code:...` to `short-url:...`.

    In both cases, backwards compatibility has been retained and the old ones are aliases for the new ones, but the old ones are considered deprecated.

### Removed
* *Nothing*

### Fixed
* [#203](https://github.com/shlinkio/shlink/issues/203) Fixed some warnings thrown while unzipping distributable files.
* [#206](https://github.com/shlinkio/shlink/issues/206) An error is now thrown during installation if any required param is left empty, making the installer display a message and ask again until a value is set.


## [1.12.0] - 2018-09-15
### Added
* [#187](https://github.com/shlinkio/shlink/issues/187) Included an API endpoint and a CLI command to delete short URLs.

    Due to the implicit danger of this operation, the deletion includes a safety check. URLs cannot be deleted if they have more than a specific amount of visits.

    The visits threshold is set to **15** by default and currently it has to be manually changed. In future versions the installation/update process will ask you about the value of the visits threshold.

    In order to change it, open the `config/autoload/delete_short_urls.global.php` file, which has this structure:

    ```php
    return [

        'delete_short_urls' => [
            'visits_threshold' => 15,
            'check_visits_threshold' => true,
        ],

    ];
    ```

    Properties are self explanatory. Change `check_visits_threshold` to `false` to completely disable this safety check, and change the value of `visits_threshold` to allow short URLs with a different number of visits to be deleted.

    Once changed, delete the `data/cache/app_config.php` file (if any) to let shlink know about the new values.

    This check is implicit for the API endpoint, but can be "disabled" for the CLI command, which will ask you when trying to delete a URL which has reached to threshold in order to force the deletion.

* [#183](https://github.com/shlinkio/shlink/issues/183) and [#190](https://github.com/shlinkio/shlink/issues/190) Included important documentation improvements in the repository itself. You no longer need to go to the website in order to see how to install or use shlink.
* [#186](https://github.com/shlinkio/shlink/issues/186) Added a small robots.txt file that prevents 404 errors to be logged due to search engines trying to index the domain where shlink is located. Thanks to [@robwent](https://github.com/robwent) for the contribution.

### Changed
* [#145](https://github.com/shlinkio/shlink/issues/145) Shlink now obfuscates IP addresses from visitors by replacing the latest octet by `0`, which does not affect geolocation and allows it to fulfil the GDPR.

    Other known services follow this same approach, like [Google Analytics](https://support.google.com/analytics/answer/2763052?hl=en) or [Matomo](https://matomo.org/docs/privacy/#step-1-automatically-anonymize-visitor-ips)

* [#182](https://github.com/shlinkio/shlink/issues/182) The short URL creation API endpoints now return the same model used for lists and details endpoints.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#188](https://github.com/shlinkio/shlink/issues/188) Shlink now allows multiple short URLs to be created that resolve to the same long URL.


## [1.11.0] - 2018-08-13
### Added
* [#170](https://github.com/shlinkio/shlink/issues/170) and [#171](https://github.com/shlinkio/shlink/issues/171) Updated `[GET /short-codes]` and `[GET /short-codes/{shortCode}]` endpoints to return more meaningful information and make their response consistent.

    The short URLs are now represented by this object in both cases:

    ```json
    {
        "shortCode": "12Kb3",
        "shortUrl": "https://doma.in/12Kb3",
        "longUrl": "https://shlink.io",
        "dateCreated": "2016-05-01T20:34:16+02:00",
        "visitsCount": 1029,
        "tags": [
            "shlink"
        ],
        "originalUrl": "https://shlink.io"
    }
    ```

    The `originalUrl` property is considered deprecated and has been kept for backward compatibility purposes. It holds the same value as the `longUrl` property.

### Changed
* *Nothing*

### Deprecated
* The `originalUrl` property in `[GET /short-codes]` and `[GET /short-codes/{shortCode}]` endpoints is now deprecated and replaced by the `longUrl` property.

### Removed
* *Nothing*

### Fixed
* *Nothing*


## [1.10.2] - 2018-08-04
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#177](https://github.com/shlinkio/shlink/issues/177) Fixed `[GET] /short-codes` endpoint returning a 500 status code when trying to filter by `tags` and `searchTerm` at the same time.
* [#175](https://github.com/shlinkio/shlink/issues/175) Fixed error introduced in previous version, where you could end up banned from the service used to resolve IP address locations.

    In order to fix that, just fill [this form](http://ip-api.com/docs/unban) including your server's IP address and your server should be unbanned.
    
    In order to prevent this, after resolving 150 IP addresses, shlink now waits 1 minute before trying to resolve any more addresses.


## [1.10.1] - 2018-08-02
### Added
* *Nothing*

### Changed
* [#167](https://github.com/shlinkio/shlink/issues/167) Shlink version is now set at build time to avoid older version numbers to be kept in newer builds.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#165](https://github.com/shlinkio/shlink/issues/165) Fixed custom slugs failing when they are longer than 10 characters.
* [#166](https://github.com/shlinkio/shlink/issues/166) Fixed unusual edge case in which visits were not properly counted when ordering by visit and filtering by search term in `[GET] /short-codes` API endpoint.
* [#174](https://github.com/shlinkio/shlink/issues/174) Fixed geolocation not working due to a deprecation on used service.
* [#172](https://github.com/shlinkio/shlink/issues/172) Documented missing filtering params for `[GET] /short-codes/{shortCode}/visits` API endpoint, which allow the list to be filtered by date range.

    For example: `https://doma.in/rest/v1/short-urls/abc123/visits?startDate=2017-05-23&endDate=2017-10-05`

* [#169](https://github.com/shlinkio/shlink/issues/169) Fixed unhandled error when parsing `ShortUrlMeta` and date fields are already `DateTime` instances.


## [1.10.0] - 2018-07-09
### Added
* [#161](https://github.com/shlinkio/shlink/issues/161) AddED support for shlink to be run with [swoole](https://www.swoole.co.uk/) via [zend-expressive-swoole](https://github.com/zendframework/zend-expressive-swoole) package

### Changed
* [#159](https://github.com/shlinkio/shlink/issues/159) Updated CHANGELOG to follow the [keep-a-changelog](https://keepachangelog.com) format
* [#160](https://github.com/shlinkio/shlink/issues/160) Update infection to v0.9 and phpstan to v 0.10

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* *Nothing*


## [1.9.1] - 2018-06-18
### Added
* [#155](https://github.com/shlinkio/shlink/issues/155) Improved the pagination object returned in lists, including more meaningful properties.
    
    * Old structure:
    
    ```json
    {
      "pagination": {
        "currentPage": 1,
        "pagesCount": 2
      }
    }
    ```
    
    * New structure:
    
    ```json
    {
      "pagination": {
        "currentPage": 2,
        "pagesCount": 13,
        "itemsPerPage": 10,
        "itemsInCurrentPage": 10,
        "totalItems": 126
      }
    }
    ```

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#154](https://github.com/shlinkio/shlink/issues/154) Fixed sizes of every result page when filtering by searchTerm
* [#157](https://github.com/shlinkio/shlink/issues/157) Background commands executed by installation process now respect the originally used php binary


## [1.9.0] - 2018-05-07
### Added
* [#147](https://github.com/shlinkio/shlink/issues/147) Allowed short URLs to be created on the fly using a single API request, including the API key in a  query param.

    This eases integration with third party services.
    
    With this feature, a simple request to a URL like `https://doma.in/rest/v1/short-codes/shorten?apiKey=[YOUR_API_KEY]&longUrl=[URL_TO_BE_SHORTENED]` would return the shortened one in JSON or plain text format.

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#139](https://github.com/shlinkio/shlink/issues/139) Ensured all core actions log exceptions


## [1.8.1] - 2018-04-07
### Added
* *Nothing*

### Changed
* [#141](https://github.com/shlinkio/shlink/issues/141) Removed workaround used in `PathVersionMiddleware`, since the bug in zend-stratigility has been fixed.

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#140](https://github.com/shlinkio/shlink/issues/140) Fixed warning thrown during installation while trying to include doctrine script


## [1.8.0] - 2018-03-29
### Added
* [#125](https://github.com/shlinkio/shlink/issues/125) Implemented a path which returns a 1px image instead of a redirection.

    Useful to track emails. Just add an image pointing to a URL like `https://doma.in/abc123/track` to any email and an invisible image will be generated tracking every time the email is opened.

* [#132](https://github.com/shlinkio/shlink/issues/132) Added infection to improve tests

### Changed
* [#130](https://github.com/shlinkio/shlink/issues/130) Updated to Expressive 3
* [#137](https://github.com/shlinkio/shlink/issues/137) Updated symfony components to v4

### Deprecated
* *Nothing*

### Removed
* [#131](https://github.com/shlinkio/shlink/issues/131) Dropped support for PHP 7

### Fixed
* *Nothing*


## [1.7.2] - 2018-03-26
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#135](https://github.com/shlinkio/shlink/issues/135) Fixed `PathVersionMiddleware` being ignored when using expressive 2.2


## [1.7.1] - 2018-03-21
### Added
* *Nothing*

### Changed
* [#128](https://github.com/shlinkio/shlink/issues/128) Upgraded to expressive 2.2

    This will ease the upcoming update to expressive 3

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#126](https://github.com/shlinkio/shlink/issues/126) Fixed `E_USER_DEPRECATED` errors triggered when using Expressive 2.2


## [1.7.0] - 2018-01-21
### Added
* [#88](https://github.com/shlinkio/shlink/issues/88) Allowed tracking of short URLs to be disabled by including a configurable query param
* [#108](https://github.com/shlinkio/shlink/issues/108) Allowed metadata to be defined when creating short codes

### Changed
* [#113](https://github.com/shlinkio/shlink/issues/113) Updated CLI commands to use `SymfonyStyle`
* [#112](https://github.com/shlinkio/shlink/issues/112) Enabled Lazy loading in CLI commands
* [#117](https://github.com/shlinkio/shlink/issues/117) Every module which throws exceptions has now its own `ExceptionInterface` extending `Throwable`
* [#115](https://github.com/shlinkio/shlink/issues/115) Added phpstan to build matrix on PHP >=7.1 envs
* [#114](https://github.com/shlinkio/shlink/issues/114) Replaced [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) dev requirement by [symfony/dotenv](https://github.com/symfony/dotenv)

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* *Nothing*


## [1.6.2] - 2017-10-25
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#109](https://github.com/shlinkio/shlink/issues/109) Fixed installation error due to typo in latest migration


## [1.6.1] - 2017-10-24
### Added
* *Nothing*

### Changed
* [#110](https://github.com/shlinkio/shlink/issues/110) Created `.gitattributes` file to define files to be excluded from distributable package

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* *Nothing*


## [1.6.0] - 2017-10-23
### Added
* [#44](https://github.com/shlinkio/shlink/issues/44) Now it is possible to set custom slugs for short URLs instead of using a generated short code
* [#47](https://github.com/shlinkio/shlink/issues/47) Allowed to limit short URLs availability by date range
* [#48](https://github.com/shlinkio/shlink/issues/48) Allowed to limit the number of visits to a short URL
* [#105](https://github.com/shlinkio/shlink/pull/105) Added option to enable/disable URL validation by response status code

### Changed
* [#27](https://github.com/shlinkio/shlink/issues/27) Added repository functional tests with dbunit
* [#101](https://github.com/shlinkio/shlink/issues/101) Now specific actions just capture very specific exceptions, and let the `ErrorHandler` catch any other unhandled exception
* [#104](https://github.com/shlinkio/shlink/issues/104) Used different templates for *requested-short-code-does-not-exist* and *route-could-not-be-match*
* [#99](https://github.com/shlinkio/shlink/issues/99) Replaced usages of `AnnotatedFactory` by `ConfigAbstractFactory`
* [#100](https://github.com/shlinkio/shlink/issues/100) Updated templates engine. Replaced twig by plates
* [#102](https://github.com/shlinkio/shlink/issues/102) Improved coding standards strictness

### Deprecated
* *Nothing*

### Removed
* [#86](https://github.com/shlinkio/shlink/issues/86) Dropped support for PHP 5

### Fixed
* [#103](https://github.com/shlinkio/shlink/issues/103) `NotFoundDelegate` now returns proper content types based on accepted content


## [1.5.0] - 2017-07-16
### Added
* [#95](https://github.com/shlinkio/shlink/issues/95) Added tags CRUD to CLI
* [#59](https://github.com/shlinkio/shlink/issues/59) Added tags CRUD to REST
* [#66](https://github.com/shlinkio/shlink/issues/66) Allowed certain information to be imported from and older shlink instance directory when updating

### Changed
* [#96](https://github.com/shlinkio/shlink/issues/96) Added namespace to functions
* [#76](https://github.com/shlinkio/shlink/issues/76) Added response examples to swagger docs
* [#93](https://github.com/shlinkio/shlink/issues/93) Improved cross domain management by using the `ImplicitOptionsMiddleware`

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#92](https://github.com/shlinkio/shlink/issues/92) Fixed formatted dates, using an ISO compliant format


## [1.4.0] - 2017-03-25
### Added
* *Nothing*

### Changed
* [#89](https://github.com/shlinkio/shlink/issues/89) Updated to expressive 2

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* *Nothing*


## [1.3.1] - 2017-01-22
### Added
* *Nothing*

### Changed
* [#82](https://github.com/shlinkio/shlink/issues/82) Enabled `FastRoute` routes cache
* [#85](https://github.com/shlinkio/shlink/issues/85) Updated year in license file
* [#81](https://github.com/shlinkio/shlink/issues/81) Added docker containers config

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#83](https://github.com/shlinkio/shlink/issues/83) Fixed short codes list: search in tags when filtering by query string
* [#79](https://github.com/shlinkio/shlink/issues/79) Increased the number of followed redirects
* [#75](https://github.com/shlinkio/shlink/issues/75) Applied `PathVersionMiddleware` only to rest routes defining it by configuration instead of code
* [#77](https://github.com/shlinkio/shlink/issues/77) Allowed defining database server hostname and port


## [1.3.0] - 2016-10-23
### Added
* [#67](https://github.com/shlinkio/shlink/issues/67) Allowed to order the short codes list
* [#60](https://github.com/shlinkio/shlink/issues/60) Accepted JSON requests in REST and used a body parser middleware to set the request's `parsedBody`
* [#72](https://github.com/shlinkio/shlink/issues/72) When listing API keys from CLI, use yellow color for enabled keys that have expired
* [#58](https://github.com/shlinkio/shlink/issues/58) Allowed to filter short URLs by tag
* [#69](https://github.com/shlinkio/shlink/issues/69) Allowed to filter short URLs by text query
* [#73](https://github.com/shlinkio/shlink/issues/73) Added tag-related endpoints to swagger file
* [#63](https://github.com/shlinkio/shlink/issues/63) Added path versioning to REST API routes

### Changed
* [#71](https://github.com/shlinkio/shlink/issues/71) Separated swagger docs into multiple files

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* *Nothing*


## [1.2.2] - 2016-08-29
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* Fixed minor bugs on CORS requests


## [1.2.1] - 2016-08-21
### Added
* *Nothing*

### Changed
* *Nothing*

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#62](https://github.com/shlinkio/shlink/issues/62) Fixed cross-domain requests in REST API


## [1.2.0] - 2016-08-21
### Added
* [#45](https://github.com/shlinkio/shlink/issues/45) Allowed to define tags on short codes, to improve filtering and classification
* [#7](https://github.com/shlinkio/shlink/issues/7) Added website previews while listing available URLs
* [#57](https://github.com/shlinkio/shlink/issues/57) Added database migrations system to improve updating between versions
* [#31](https://github.com/shlinkio/shlink/issues/31) Added support for other database management systems by improving the `EntityManager` factory
* [#51](https://github.com/shlinkio/shlink/issues/51) Generated build process to create app package and ease distribution
* [#38](https://github.com/shlinkio/shlink/issues/38) Defined installation script. It will request dynamic data on the fly so that there is no need to define env vars
* [#55](https://github.com/shlinkio/shlink/issues/55) Created update script which does not try to create a new database

### Changed
* [#54](https://github.com/shlinkio/shlink/issues/54) Added cache namespace to prevent name collisions with other apps in the same environment
* [#29](https://github.com/shlinkio/shlink/issues/29) Used the [acelaya/ze-content-based-error-handler](https://github.com/acelaya/ze-content-based-error-handler) package instead of custom error handler implementation

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#53](https://github.com/shlinkio/shlink/issues/53) Fixed entities database interoperability
* [#52](https://github.com/shlinkio/shlink/issues/52) Added missing htaccess file for apache environments


## [1.1.0] - 2016-08-09
### Added
* [#46](https://github.com/shlinkio/shlink/issues/46) Defined a route that returns a QR code representing the shortened URL.

    In order to get the QR code URL, use a pattern like `https://doma.in/abc123/qr-code`

* [#32](https://github.com/shlinkio/shlink/issues/32) Added support for other cache adapters by improving the Cache factory
* [#14](https://github.com/shlinkio/shlink/issues/14) Added logger and enabled errors logging
* [#13](https://github.com/shlinkio/shlink/issues/13) Improved REST authentication

### Changed
* [#41](https://github.com/shlinkio/shlink/issues/41) Cached the "short code" => "URL" map to prevent extra DB hits
* [#39](https://github.com/shlinkio/shlink/issues/39) Changed copyright from "Alejandro Celaya" to "Shlink" in error pages
* [#42](https://github.com/shlinkio/shlink/issues/42) REST endpoints that need to find *something* now return a 404 when it is not found
* [#35](https://github.com/shlinkio/shlink/issues/35) Updated CLI commands to use the same PHP namespace as the one used for the command name

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* [#40](https://github.com/shlinkio/shlink/issues/40) Taken into account the `X-Forwarded-For` header in order to get the visitor information, in case the server is behind a load balancer or proxy


## [1.0.0] - 2016-08-01
### Added
* [#33](https://github.com/shlinkio/shlink/issues/33) Created a command that generates a short code charset by randomizing the default one
* [#23](https://github.com/shlinkio/shlink/issues/23) Translated application literals
* [#21](https://github.com/shlinkio/shlink/issues/21) Allowed to filter visits by date range
* [#4](https://github.com/shlinkio/shlink/issues/4) Added installation steps
* [#12](https://github.com/shlinkio/shlink/issues/12) Improved code coverage

### Changed
* [#15](https://github.com/shlinkio/shlink/issues/15) HTTP requests now return JSON/HTML responses for errors (4xx and 5xx) based on `Accept` header
* [#22](https://github.com/shlinkio/shlink/issues/22) Now visits locations data is saved on a `visit_locations` table
* [#20](https://github.com/shlinkio/shlink/issues/20) Injected cross domain headers in response only if the `Origin` header is present in the request
* [#11](https://github.com/shlinkio/shlink/issues/11) Separated code into multiple modules
* [#18](https://github.com/shlinkio/shlink/issues/18) Grouped routable middleware in an Action namespace
* [#6](https://github.com/shlinkio/shlink/issues/6) Project no longer depends on [zendframework/zend-expressive-helpers](https://github.com/zendframework/zend-expressive-helpers) package
* [#30](https://github.com/shlinkio/shlink/issues/30) Replaced the "services" first level config entry by "dependencies", in order to fulfill default Expressive naming
* [#25](https://github.com/shlinkio/shlink/issues/25) Replaced "Middleware" suffix on routable middlewares by "Action"
* [#19](https://github.com/shlinkio/shlink/issues/19) Changed the vendor and app namespace from `Acelaya\UrlShortener` to `Shlinkio\Shlink`

### Deprecated
* *Nothing*

### Removed
* [#36](https://github.com/shlinkio/shlink/issues/36) Removed hhvm from the CI matrix since it doesn't support array constants and will fail

### Fixed
* [#24](https://github.com/shlinkio/shlink/issues/24) Prevented duplicated short codes errors because of the case insensitive behavior on MySQL


## [0.2.0] - 2016-08-01
### Added
* [#8](https://github.com/shlinkio/shlink/issues/8) Created a REST API
* [#10](https://github.com/shlinkio/shlink/issues/10) Added more CLI functionality
* [#5](https://github.com/shlinkio/shlink/issues/5) Created a CHANGELOG file

### Changed
* [#9](https://github.com/shlinkio/shlink/issues/9) Used [symfony/console](https://github.com/symfony/console) to dispatch console requests, instead of trying to integrate the process with expressive

### Deprecated
* *Nothing*

### Removed
* *Nothing*

### Fixed
* *Nothing*
