# Contributing

Thank you for considering contributing to Laravel With Jiri.

## Getting started

1. Fork the repository.
2. Clone your fork and follow the [Development setup](README.md#development).
3. Create a branch: `git checkout -b my-feature`.
4. Make your changes, write/update tests.
5. Run the test suite: `composer run test`.
6. Run static analysis: `vendor/bin/phpstan analyse`.
7. Run Rector: `vendor/bin/rector process --dry-run`.
8. Format code: `vendor/bin/pint`.
9. Push and open a pull request.

## Guidelines

- Follow the existing code style and conventions.
- Write tests for all new functionality (Pest, TDD).
- Keep pull requests focused — one feature or fix per PR.
- Update documentation (README, inline PHPDoc) when changing public behaviour.
- Ensure all tests pass before requesting review.
