
# Change Log


## [1.1.0] - 2021-09-24

### Added
 - Added `CHANGELOG.md`

### Changed

- Refactored `src/Mapper/SendGridApiMapper.php` to `src/Repository/SendGridApiRepository.php`

### Fixed

- Lowed default minimum score threshold to `0.15` to from `0.30` as it was blocking valid emails
- Fixed incorrect namespace in `tests/Unit/EmailValidationTest.php`

## [1.0.1] - 2021-09-20

### Changed
- Reformatted code
