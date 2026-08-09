# AGENTS.md

This file provides instructions for AI agents (like Mistral Vibe) when working on this repository.

## Project Overview

**prophecy-php** is a PHP testing library that enables mocking of built-in PHP functions using Prophecy-style syntax for PHPUnit tests.

- **Language**: PHP 8.2+
- **Type**: Testing library (type: "testing" in composer.json)
- **Maintainer**: Heiko Jerichen (heiko@jerichen.de)
- **License**: MIT

## Repository Structure

```
.
├── src/                    # Source code
│   ├── ArgumentEvaluator.php
│   ├── Exception/          # Exception classes
│   ├── FunctionCallDetector.php
│   ├── FunctionDelegation.php
│   ├── FunctionProphecy.php
│   ├── FunctionProphecyStorage.php
│   ├── FunctionRevealer.php
│   ├── NamespaceProphecy.php
│   ├── PHPBuiltInFunctions.php
│   ├── PHPProphet.php
│   ├── PHPProphetTrait.php
│   └── function.tpl
├── tests/                  # Test files
│   ├── Integration/
│   └── Unit/
├── .github/workflows/     # GitHub Actions CI
├── composer.json          # Composer configuration
├── psalm.xml              # Psalm static analysis config
└── phpunit.xml.dist       # PHPUnit configuration
```

## Development Environment

### Dependencies

- **Runtime**: `phpspec/prophecy:^1.20`, `phpunit/php-text-template:^2.0 | ^3.0 | ^4.0 | ^5.0`
- **Development**: `phpunit/phpunit:^12.5`, `phpspec/prophecy-phpunit:^2.0`, `vimeo/psalm:^6.16`, `psalm/plugin-phpunit:^0.19.2`

### Tools

- **Testing**: PHPUnit 12.5+
- **Static Analysis**: Psalm with phpunit plugin
- **CI**: GitHub Actions
- **Code Coverage**: php-coveralls via Coveralls

## Commands

### Testing

```bash
# Run all tests
vendor/bin/phpunit

# Run with coverage
vendor/bin/phpunit --coverage-clover build/logs/clover.xml
```

### Static Analysis

```bash
# Run Psalm
vendor/bin/psalm

# Run Psalm with GitHub-friendly output
vendor/bin/psalm --output-format=github
```

### Composer

```bash
# Validate composer.json
composer validate

# Install/update dependencies
composer update
composer update --prefer-lowest
```

## Code Style & Standards

### PHP Version

- Target PHP 8.2+
- Code must be compatible with PHP 8.2, 8.3, 8.4, and 8.5

### SOLID Principles & Design Guidelines

All code must follow SOLID principles and clean code practices:

- **Single Responsibility Principle (SRP)**: Each class and method should have one, and only one, responsibility. One method does one thing.
- **Open/Closed Principle (OCP)**: Classes should be open for extension but closed for modification.
- **Liskov Substitution Principle (LSP)**: Subtypes must be substitutable for their base types.
- **Interface Segregation Principle (ISP)**: Clients should not be forced to depend on interfaces they do not use.
- **Dependency Inversion Principle (DIP)**: High-level modules should not depend on low-level modules; both should depend on abstractions.

**Additional guidelines**:
- Methods should be small and focused on a single task
- Avoid god classes/objects that do too much
- Favor composition over inheritance
- Use dependency injection where appropriate
- Keep method parameters minimal (ideally 3 or fewer)
- Return early rather than nesting conditions

### Testing

- Tests are located in `tests/` directory
- Test files follow PSR-4 autoloading under `HJerichen\ProphecyPHP\Tests\`
- Use PHPUnit 12.5+ features
- Tests should cover both Unit and Integration scenarios

### Static Analysis

- Psalm is configured with error level 1
- Multiple issues are suppressed via `issueHandlers` in psalm.xml:
  - `RedundantPropertyInitializationCheck`
  - `PropertyNotSetInConstructor`
  - `MissingConstructor`
  - `InvalidDocblock`
  - `MissingOverrideAttribute`
  - `UndefinedThisPropertyFetch`
  - `ClassMustBeFinal`
  - `InternalProperty`
  - `InternalMethod`
  - `MixedMethodCall`
  - `UndefinedMagicMethod`
- Plugins: `Psalm\PhpUnitPlugin\Plugin`

### File Naming

- Source files: PascalCase class names matching file names
- Test files: Follow same naming convention as source files
- Namespace: `HJerichen\ProphecyPHP\` for source, `HJerichen\ProphecyPHP\Tests\` for tests

## Workflow Instructions for Agents

### Before Making Changes

1. **Read relevant files first**:
   - Read the file you're editing end-to-end
   - Read related test files
   - Read files that call or depend on your target

2. **Understand the architecture**:
   - `PHPProphetTrait` is the main entry point for users
   - `PHPProphet` manages prophecy creation
   - `NamespaceProphecy` represents a mocked namespace
   - `FunctionProphecy` represents a mocked function
   - `FunctionProphecyStorage` stores function prophecies

3. **Run tests**: Always run PHPUnit tests to verify changes don't break existing functionality

4. **Run static analysis**: Run Psalm to catch potential issues early

### Editing Guidelines

- **Match existing style**: Follow the existing code style (indentation, naming conventions, etc.)
- **Minimal changes**: Only modify what's necessary; don't refactor unrelated code
- **Type safety**: Leverage PHP 8.2+ type features (return types, parameter types, etc.)
- **Documentation**: Update docblocks when changing public API behavior

### Verification

Before considering a task complete:
1. All relevant tests must pass
2. Psalm must pass (or only have suppressed errors)
3. The code must produce expected output
4. User acceptance criteria must be met

### Git Workflow

- Create feature/fix branches from `master`
- Use descriptive commit messages
- Commit messages should be co-authored with Mistral Vibe when applicable

## Key Classes and Their Purpose

| Class                     | Purpose                                                      |
|---------------------------|--------------------------------------------------------------|
| `PHPProphetTrait`         | Trait for test cases to enable PHP function mocking          |
| `PHPProphet`              | Main class for creating and managing PHP function prophecies |
| `NamespaceProphecy`       | Represents a namespace with mocked functions                 |
| `FunctionProphecy`        | Represents a single mocked function                          |
| `FunctionProphecyStorage` | Stores and manages function prophecy definitions             |
| `FunctionRevealer`        | Applies the mocked functions (makes them active)             |
| `FunctionCallDetector`    | Detects when mocked functions are called                     |
| `FunctionDelegation`      | Handles delegation of mocked function calls                  |
| `ArgumentEvaluator`       | Evaluates arguments against expected values                  |
| `PHPBuiltInFunctions`     | Contains definitions of PHP built-in functions               |

## Common Patterns

### Creating a Mock

```php
$this->php = $this->prophesizePHP(__NAMESPACE__);
$this->php->time()->willReturn(1234);
$this->php->reveal();
```

### Multiple Return Values

```php
$this->php->time()->willReturn(1234, 1235, 1236);
```

### Argument Matching

```php
$this->php->date('Y', 1234)->willReturn('1970');
$this->php->file_put_contents('/to/foo.txt', 'content')->shouldBeCalledOnce();
```

## Known Issues & Workarounds

- **PHP Bug 64346**: Mocking may not work if the original function is called in the namespace before mocking it
- **Workaround**: Use `prepare()` method in setUp to pre-register functions:
  ```php
  $this->php->prepare('time', 'date');
  ```

## Restrictions

- Only unqualified function calls in a namespace context can be mocked
- Functions must be defined in `PHPBuiltInFunctions.php` to be mockable
