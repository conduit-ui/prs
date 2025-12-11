<?php

declare(strict_types=1);

namespace ConduitUI\Prs;

use ConduitUI\Connector\GithubConnector;
use ConduitUI\Prs\DataTransferObjects\PullRequest;

class PullRequests
{
    public function __construct(
        protected GithubConnector $connector,
        protected string $owner,
        protected string $repo
    ) {}

    /**
     * Get a pull request by number
     */
    public function get(int $number): PullRequest
    {
        $response = $this->connector->get("/repos/{$this->owner}/{$this->repo}/pulls/{$number}");

        return PullRequest::fromArray($response);
    }

    /**
     * List pull requests with optional filters
     */
    public function list(array $filters = []): array
    {
        $queryParams = http_build_query(array_merge([
            'state' => 'open',
            'sort' => 'created',
            'direction' => 'desc',
        ], $filters));

        $response = $this->connector->get("/repos/{$this->owner}/{$this->repo}/pulls?{$queryParams}");

        return array_map(fn ($pr) => PullRequest::fromArray($pr), $response);
    }

    /**
     * Filter pull requests by state
     */
    public function where(string $key, mixed $value): self
    {
        // This will be implemented to support fluent filtering
        // e.g., PR::where('state', 'open')->author('jordanpartridge')->get()
        return $this;
    }

    /**
     * Filter by author
     */
    public function author(string $username): self
    {
        return $this->where('author', $username);
    }

    /**
     * Filter by state
     */
    public function state(string $state): self
    {
        return $this->where('state', $state);
    }

    /**
     * Get only open pull requests
     */
    public function open(): self
    {
        return $this->state('open');
    }

    /**
     * Get only closed pull requests
     */
    public function closed(): self
    {
        return $this->state('closed');
    }

    /**
     * Create a new pull request
     */
    public function create(array $data): PullRequest
    {
        $response = $this->connector->post("/repos/{$this->owner}/{$this->repo}/pulls", $data);

        return PullRequest::fromArray($response);
    }

    /**
     * Update a pull request
     */
    public function update(int $number, array $data): PullRequest
    {
        $response = $this->connector->patch("/repos/{$this->owner}/{$this->repo}/pulls/{$number}", $data);

        return PullRequest::fromArray($response);
    }

    /**
     * Merge a pull request
     */
    public function merge(int $number, ?string $commitMessage = null, ?string $mergeMethod = null): bool
    {
        $data = array_filter([
            'commit_message' => $commitMessage,
            'merge_method' => $mergeMethod,
        ]);

        $response = $this->connector->put("/repos/{$this->owner}/{$this->repo}/pulls/{$number}/merge", $data);

        return $response['merged'] ?? false;
    }

    /**
     * Close a pull request
     */
    public function close(int $number): PullRequest
    {
        return $this->update($number, ['state' => 'closed']);
    }
}
