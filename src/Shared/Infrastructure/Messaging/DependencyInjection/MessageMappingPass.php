<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Messaging\DependencyInjection;

use Illuminate\Support\Arr;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Whalar\Shared\Domain\Messaging\Message;
use Whalar\Shared\Infrastructure\Messaging\Transport\Serialization\InMemoryMessageMapping;

use function Lambdish\Phunctional\reduce;

final class MessageMappingPass implements CompilerPassInterface
{
    private const DEFINITION_ID = 'whalar.message_mapping';
    private const MESSAGE_CLASS = Message::class;

    public function process(ContainerBuilder $container): void
    {
        $srcDir = sprintf('%s/src/', $container->getParameter('kernel.project_dir'));
        $srcFiles = $this->getFilesFromDirectory($srcDir);

        $definition = $this->getDefinition($srcFiles);

        $container->addDefinitions([
            self::DEFINITION_ID => $definition,
        ]);
    }

    /** @return array<int, string> */
    private function getFilesFromDirectory(string $path): array
    {
        if (false === is_dir($path)) {
            throw new \RuntimeException('Could not get files from directory: The path is not valid');
        }

        $files = [];

        if (false !== ($dh = opendir($path))) {
            while (false !== ($filename = readdir($dh))) {
                $isDirectory = is_dir($path.$filename);

                if (true === $isDirectory && false === \in_array($filename, ['.', '..'], true)) {
                    $files = array_merge(
                        $files,
                        $this->getFilesFromDirectory($path.$filename.'/'),
                    );

                    continue;
                }

                if (false !== is_dir($path.$filename)) {
                    continue;
                }

                if (0 === preg_match("/^.*\.(php)$/", $filename)) {
                    continue;
                }

                $files[] = $path.$filename;
            }

            closedir($dh);
        }

        return $files;
    }

    /** @param array<int, string> $files */
    private function getDefinition(array $files): Definition
    {
        $classes = $this->getMessageSubclasses($files);

        $mapping = $this->generateMapping($classes);

        return new Definition(InMemoryMessageMapping::class, [$mapping]);
    }

    /**
     * @param array<int, string> $files
     *
     * @return array<mixed>
     */
    private function getMessageSubclasses(array $files): array
    {
        foreach ($files as $file) {
            require_once $file;
        }

        return (array) reduce(
            static function (array $classes, string $class): array {
                try {
                    // @phpstan-ignore-next-line
                    $reflection = new \ReflectionClass($class);
                } catch (\ReflectionException) {
                    return $classes;
                }

                if (!$reflection->isSubclassOf(self::MESSAGE_CLASS)) {
                    return $classes;
                }

                if (!$reflection->isFinal()) {
                    return $classes;
                }

                $classes[] = $class;

                return $classes;
            },
            get_declared_classes(),
            [],
        );
    }

    /**
     * @param array<int, class-string<Message>> $messageClasses
     *
     * @return array<string, class-string<Message>>
     */
    private function generateMapping(array $messageClasses): array
    {
        return reduce(
            static fn (array $mapping, string $message): array => Arr::dot(
                data_set($mapping, $message::messageChannel(), $message),
            ),
            $messageClasses,
            [],
        );
    }
}
