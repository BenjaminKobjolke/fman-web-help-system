<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;

final class ProductNameTest extends TestCase
{
    public function testHelpArticlesUseTheCurrentProductNameInProse(): void
    {
        $files = glob(dirname(__DIR__) . '/content/help/*.md');
        self::assertNotFalse($files);
        self::assertNotEmpty($files);

        foreach ($files as $file) {
            $content = file_get_contents($file);
            self::assertNotFalse($content);

            $content = $this->maskFencedCodeBlocks($content);
            $content = preg_replace_callback(
                '/(`+)(?!`).*?\1(?!`)/s',
                static fn (array $matches): string => preg_replace('/[^\r\n]/', ' ', $matches[0]) ?? $matches[0],
                $content,
            );
            self::assertNotNull($content);

            foreach (preg_split('/\R/', $content) ?: [] as $lineNumber => $line) {
                if (preg_match('/\bfman\b(?!\+\+)/i', $line) === 1) {
                    self::fail(sprintf(
                        '%s:%d still uses the old product name: %s',
                        basename($file),
                        $lineNumber + 1,
                        trim($line),
                    ));
                }
            }
        }
    }

    private function maskFencedCodeBlocks(string $content): string
    {
        $parts = preg_split('/(\r\n|\n|\r)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        self::assertNotFalse($parts);

        $fenceCharacter = null;
        $fenceLength = 0;

        foreach ($parts as $index => $part) {
            if ($index % 2 !== 0) {
                continue;
            }

            if ($fenceCharacter === null) {
                if (preg_match('/^ {0,3}(`{3,}|~{3,})/', $part, $matches) === 1) {
                    $fenceCharacter = $matches[1][0];
                    $fenceLength = strlen($matches[1]);
                    $parts[$index] = str_repeat(' ', strlen($part));
                }

                continue;
            }

            $parts[$index] = str_repeat(' ', strlen($part));
            $closingFence = '/^ {0,3}' . preg_quote($fenceCharacter, '/') . '{' . $fenceLength . ',}[ \t]*$/';
            if (preg_match($closingFence, $part) === 1) {
                $fenceCharacter = null;
                $fenceLength = 0;
            }
        }

        return implode('', $parts);
    }
}
