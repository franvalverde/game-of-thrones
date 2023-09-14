<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Json;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class Json implements \Stringable
{
    private mixed $content;

    private function __construct(string $string)
    {
        $this->setContent($string);
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }

    public static function fromData(mixed $data): self
    {
        return self::fromString(json_encode($data, \JSON_THROW_ON_ERROR));
    }

    public function __toString(): string
    {
        return $this->print(false);
    }

    public function content(): mixed
    {
        return $this->content;
    }

    /** @return array<mixed> */
    public function toArray(): array
    {
        return (array) $this->content;
    }

    public function print(bool $pretty = true): string
    {
        $flags = \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE;

        if (true === $pretty && \defined('JSON_PRETTY_PRINT')) {
            $flags |= \JSON_PRETTY_PRINT;
        }

        $result = json_encode($this->content, $flags);

        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(json_last_error_msg());
        }

        return $result;
    }

    public function read($expression, PropertyAccessorInterface $accessor): mixed
    {
        $expression = \is_array($this->content)
            ? preg_replace('/^root/', '', (string) $expression)
            : preg_replace('/^root./', '', (string) $expression);

        $expression ??= '';

        // If root asked, we return the entire content
        if ('' === trim($expression)) {
            return $this->content;
        }

        return $accessor->getValue($this->content, $expression);
    }

    private function setContent(string $string): void
    {
        $content = json_decode($string);

        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf('Unable to parse string into JSON: %s', json_last_error_msg()));
        }

        $this->content = $content;
    }
}
