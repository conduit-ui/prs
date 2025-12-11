# conduit-ui/prs

Pull Request interface for the conduit-ui ecosystem.

## Installation

```bash
composer require conduit-ui/prs
```

## Usage

### Basic Setup

```php
use ConduitUI\Connector\GithubConnector;
use ConduitUI\Prs\PullRequests;

$connector = new GithubConnector('your-github-token');
$prs = new PullRequests($connector, 'owner', 'repo');
```

### Getting Pull Requests

```php
// Get a specific pull request
$pr = $prs->get(123);

// List all open pull requests
$openPrs = $prs->list(['state' => 'open']);

// List with fluent interface
$myPrs = $prs->open()->author('jordanpartridge')->get();
```

### Creating Pull Requests

```php
$pr = $prs->create([
    'title' => 'Add new feature',
    'body' => 'This PR adds...',
    'head' => 'feature-branch',
    'base' => 'main',
]);
```

### Updating Pull Requests

```php
$pr = $prs->update(123, [
    'title' => 'Updated title',
    'body' => 'Updated description',
]);
```

### Merging Pull Requests

```php
// Simple merge
$prs->merge(123);

// Merge with options
$prs->merge(123,
    commitMessage: 'Custom merge commit message',
    mergeMethod: 'squash'
);
```

### Closing Pull Requests

```php
$pr = $prs->close(123);
```

### Working with Pull Request Data

```php
$pr = $prs->get(123);

// Check status
if ($pr->isOpen()) {
    // ...
}

if ($pr->isMerged()) {
    // ...
}

if ($pr->isDraft()) {
    // ...
}

// Access data
echo $pr->title;
echo $pr->user->login;
echo $pr->createdAt->format('Y-m-d');
```

## Design Philosophy

This package follows Taylor Otwell's package design principles:

- **Expressive API**: Methods read like English (`$pr->approve()`, `$pr->requestChanges()`)
- **Fluent interfaces**: Enable chaining (`PR::where('state', 'open')->author('user')->get()`)
- **Clean DTOs**: Strongly-typed data transfer objects for all API responses
- **Minimal dependencies**: Only depends on conduit-ui/connector
- **Convention over configuration**: Sensible defaults, minimal setup required

## Available DTOs

- `PullRequest` - Complete pull request data
- `Review` - Pull request review data
- `Comment` - Pull request comment data
- `CheckRun` - CI/CD check run data
- `User` - GitHub user data
- `Label` - Issue/PR label data
- `Repository` - Repository data
- `Head` / `Base` - Branch reference data

## Testing

```bash
composer test
```

## Code Style

```bash
composer format
```

## Static Analysis

```bash
composer analyse
```

## License

MIT
