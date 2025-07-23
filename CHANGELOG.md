# Changelog

## [2.3.0] - 2025-07-21
### Added
- Kirby 5 compatibility
- `fields/repeater` blueprint


## [2.2.0] - 2025-03-17
### Changed
- updated styles (added gaps between blocks)


## [2.1.0] - 2024-10-07
### Added
- Nested blocks support
- page-builder.default & page-builder-wysiwyg.default field blueprints to enable field definitions in blueprints instead of options
- page-builder snippet

### Changed
- moved translations to translations folder

### Fixed
- standardized some translation keys


## [2.0.0] - 2024-01-19
### Added
- Kirby 4 support
    - updated styles
- block templates support

### Changed
- internal class names
    - PageBuilderBlock -> Block
    - PageBuilderBlockDefinition -> BlockDefinition

### Fixed
- block controller now gets correct data

### Removed
- Settings tab is not shown by default.
    - Use `layouts/page-builder-block.yml` blueprint to override the default block blueprint.


## [1.0.2] - 2023-08-12
### Changed
- PageBuilderBlock method toHtml now throws errors instead of returning empty string


## [1.0.1] - 2023-02-20
### Fixed
- Type errors in PageBuilderBlockDefinition.php


## [1.0.0] - 2023-02-20
### Added
- Initial release
