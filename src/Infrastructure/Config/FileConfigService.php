<?php

declare(strict_types=1);

namespace App\Infrastructure\Config;

class FileConfigService
{
    private string $configDir;

    public function __construct(string $configDir)
    {
        $this->configDir = $configDir;
    }

    /**
     * @return array<string>
     */
    public function listFiles(string $directory, string $pattern): array
    {
        $path = $this->configDir . '/' . $directory;
        if (!is_dir($path)) {
            return [];
        }

        $result = glob($path . '/' . $pattern);

        return $result === false ? [] : $result;
    }

    public function readConfigFile(string $path): string
    {
        $fullPath = $this->configDir . '/' . $path;
        if (!file_exists($fullPath)) {
            throw new \RuntimeException("Config file not found: $path");
        }

        $content = file_get_contents($fullPath);
        if ($content === false) {
            throw new \RuntimeException("Failed to read config file: $path");
        }

        return $content;
    }

    public function writeConfigFile(string $path, string $content): void
    {
        $fullPath = $this->configDir . '/' . $path;
        $dir = dirname($fullPath);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($fullPath, $content);
    }

    public function removeDirectory(string $directory): void
    {
        $path = $this->configDir . '/' . $directory;
        if (!is_dir($path)) {
            return;
        }

        $files = array_diff((array) scandir($path), ['.', '..']);
        foreach ($files as $file) {
            if (!is_string($file)) {
                continue;
            }
            $filePath = $path . '/' . $file;
            if (!is_string($filePath)) {
                continue;
            }
            if (is_dir($filePath)) {
                $this->removeDirectory($directory . '/' . $file);
            } else {
                unlink($filePath);
            }
        }

        if (is_string($path)) {
            rmdir($path);
        }
    }

    public function ensureDirectoryExists(string $directory): void
    {
        $path = $this->configDir . '/' . $directory;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
}
