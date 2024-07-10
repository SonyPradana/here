# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.2.0-beta0] - 2024-07-10
### Removed
- Removed support php 7.4.

### Changed
- Use split repository instead using full library (sonypradana/collection, sonypradana/console).

## [0.1.1] - 2022-10-02
### Changed
- Refactor new `ArrayStyle::class` print style.

## [0.1.0] - 2022-10-01
### Added
- Added suport reporting using socket ([#6](https:github.com/sonypradana/here/pull/6)).
- Added `ServeCommand::class` provide commad for setup config and serve socket.
- Added `Config::set()` to save new or modified config file.
- Added class obeject printer `ClassStyle::class`.

### Changed
- Added parameter to load file config `Config::load($config_location)`.
- Sanitize variable before print out `VarPrinter::sanitize()`.

## [0.0.8] - 2022-08-27
### Added
- Added `Here::dumpIf()` dump snapshot if condition given as return true.

## [0.0.7] - 2022-08-25
### Fixed
- Fixed `Here::dump()` variable always printed.

## [0.0.6] - 2022-08-25
### Added
- Added configuration for print var `Here::dump()` end of line capture code.

## [0.0.5] - 2022-08-25
### Added
- Added helper `work` shorthand for `here()->dump()`.
- Styling var output in `Here::dump()`.

## [0.0.4] - 2022-08-22
### Added
- Added config using file (`./here.config.json`).

### Changed
- `Here::count()` group by group name and file/line.

## [0.0.3] - 2022-08-13
### Added
- Added `JsonPrinter` send as json/array.

## [0.0.2] - 2022-08-13
### Changed
- Load file from cached file.

## [0.0.1] - 2022-08-08
### Added
- first commit.
