<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging\AsyncApi;

use Assert\Assertion;
use Assert\AssertionFailedException;
use JetBrains\PhpStorm\Pure;
use Stringable;

final class AsyncApiChannel implements Stringable
{
    private string $organization;
    private string $service;
    private int $messageVersion;
    private string $messageType;
    private string $resource;
    private string $action;

    /** @throws AssertionFailedException */
    private function __construct(
        string $organization,
        string $service,
        int $messageVersion,
        string $messageType,
        string $resource,
        string $action,
    ) {
        $this->setOrganization($organization);
        $this->setService($service);
        $this->setMessageVersion($messageVersion);
        $this->setMessageType($messageType);
        $this->setResource($resource);
        $this->setAction($action);
    }

    /** @throws AssertionFailedException */
    public static function from(
        string $organization,
        string $service,
        int $messageVersion,
        string $messageType,
        string $resource,
        string $action,
    ): self {
        return new self($organization, $service, $messageVersion, $messageType, $resource, $action);
    }

    /** @throws AssertionFailedException */
    public static function fromString(string $string): self
    {
        [$businessName, $department, $version, $type, $aggregateName, $action] = explode('.', $string);

        return new self($businessName, $department, (int) $version, $type, $aggregateName, $action);
    }

    public function organization(): string
    {
        return $this->organization;
    }

    public function service(): string
    {
        return $this->service;
    }

    public function messageVersion(): int
    {
        return $this->messageVersion;
    }

    public function messageType(): string
    {
        return $this->messageType;
    }

    public function resource(): string
    {
        return $this->resource;
    }

    public function action(): string
    {
        return $this->action;
    }

    public function format(): string
    {
        return sprintf(
            '%s.%s.%s.%s.%s.%s',
            $this->organization,
            $this->service,
            $this->messageVersion,
            $this->messageType,
            $this->resource,
            $this->action,
        );
    }

    #[Pure]
    public function __toString(): string
    {
        return $this->format();
    }

    /** @throws AssertionFailedException */
    private function setOrganization(string $businessName): void
    {
        Assertion::notBlank($businessName);

        $this->organization = trim($businessName);
    }

    /** @throws AssertionFailedException */
    private function setService(string $department): void
    {
        Assertion::notBlank($department);

        $this->service = trim($department);
    }

    private function setMessageVersion(int $version): void
    {
        $this->messageVersion = $version;
    }

    /** @throws AssertionFailedException */
    private function setMessageType(string $type): void
    {
        Assertion::choice($type, ['domain_event', 'command', 'query']);

        $this->messageType = $type;
    }

    /** @throws AssertionFailedException */
    private function setResource(string $aggregateName): void
    {
        Assertion::notBlank($aggregateName);

        $this->resource = trim($aggregateName);
    }

    /** @throws AssertionFailedException */
    private function setAction(string $action): void
    {
        Assertion::notBlank($action);

        $this->action = trim($action);
    }
}
