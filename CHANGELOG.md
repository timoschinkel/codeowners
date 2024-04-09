# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Removed
- Support for PHP 7 has been removed - [#27](https://github.com/timoschinkel/codeowners/pull/27)

## [2.1.0] - 2022-06-29
### Added
- Added support for trailing `/**` and leading `**/` patterns - [#20](https://github.com/timoschinkel/codeowners/pull/20) by martinssipenko

### Changed
- Update Psalm and remove deprecated totallyTyped attribute - [#21](https://github.com/timoschinkel/codeowners/pull/21) by martinssipenko

## [2.0.0] - 2021-10-20
### Added
- Added `ParserInterface::parseString()` - [Issue #16](https://github.com/timoschinkel/codeowners/issues/16) by WyriHaximus
- Inspections for PHP 8.1

### Changed
- Move inspections from Travis to Github Actions
- Renamed `ParserInterface::parse()` to `ParserInterface::parseFile()`

## [1.2.0] - 2020-11-29
### Updated
- Update PHPUnit from `^7.5` to `^9.4` in order to support PHP 8
- Update PHP version requirement to `^7.3 || ^8.0`
- Run Travis on PHP versions 7.3, 7.4 and 8.0
- Update Psalm from `^3.0` to `^4.2`

## [1.1.0] - 2020-01-10
### Added
- `\CodeOwners\ParserInterface` to enable injection of the parser

## [1.0.1] - 2020-01-08
### Added
- Inspections on PHP 7.4

### Changed
- Upgraded from PSR-2 coding style to PSR-12

## [1.0.0] - 2019-09-23
Set PHP requirements.

## Changed
- Required PHP version set to `^7.1`

## [0.2.1] - 2019-07-1
Updated the source after mutation testing with [Infection](https://github.com/infection/infection)

### Changed 
- Replaced some occurrences of `mb_substr` with `substr` as prior checks ensure no multi byte characters are in the string
- Changed some testing expectations to be more strict

## [0.2.0] - 2019-02-26
### Added
- `vimeo/psalm` as development dependency
- `squizlabs/php_codesniffer` as development dependency
- Docblock for `\CodeOwners\Parser::getReadHandle()` as `resource` cannot be type hinted
- `.gitattributes` to prevent unnecessary files in archive

### Changed
- Apply fixes to be compliant with PSR-2 coding style

## [0.1.0] - 2019-02-26
### Added
- Initial version of code, including unit tests
- README
- CHANGELOG
- Composer configuration
