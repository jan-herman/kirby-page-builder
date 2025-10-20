# Changelog

## [2.4.0] - 2025-10-14
### Added
- support for new virtual js modules in jan-herman/kirby-vite v.2.4
    - `blocksDirectoryVite` option (relative to Vite's rootDir, defaults to 'blocks')
    - `viteEntryStyle()` & `viteEntryScript()` methods in BlockDefinition

### Changed
- `blocksDirectory` option now accepts callable


## [2.3.3] - 2025-09-12
### Fixed
- Bug: Field named 'content' breaks PagwBuilder->pageBlocks() method


## [2.3.2] - 2025-09-05
### Fixed
- Bug: Plugin crashes website when none of the blocks has a template


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
