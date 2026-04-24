<?php

namespace Config;

use ReflectionClass;
use ReflectionObject;

class ConfigLoader
{
    protected string $rootDirectory = __DIR__ . DIRECTORY_SEPARATOR . "app";
    protected string | null $environment = null;
    protected string $envPrefix = "ABELOHOST";

    protected static ?ConfigLoader $default = null;

    public static function defaultLoader(): static
    {
        if (is_null(static::$default)) {
            static::$default = new static();
        }
        return static::$default;
    }

    public function setEnvironment(string | null $environment): void
    {
        $this->environment = $environment;
    }

    public function getEnvironment(): string
    {
        if (is_null($this->environment)) {
            $this->setEnvironment("development");
        }
        return $this->environment;
    }

    public function setRootDirectory(string $rootDirectory): void
    {
        $this->rootDirectory = $rootDirectory;
    }

    public function setPrefix(string $prefix): void
    {
        $this->envPrefix = $prefix;
    }

    public function __construct()
    {
    }

    public function load(Config $target): void
    {
        if (!$target->isLoaded()) {
            $filesToLoad = [
                ".env",
                ".env." . $this->getEnvironment(),
            ];
            $filteredEnv = array_filter(
                getenv(),
                fn (string $key) => str_starts_with($key, $this->envPrefix . "_"),
                ARRAY_FILTER_USE_KEY
            );
            $loadedConfig = [];
            foreach ($filteredEnv as $envKey => $envValue) {
                $loadedConfig[substr($envKey, strlen($this->envPrefix) + 1)] = $envValue;
            }
            foreach ($filesToLoad as $file) {
                $filePath = realpath($this->rootDirectory . DIRECTORY_SEPARATOR . $file);
                if (file_exists($filePath)) {
                    $fileConfig = parse_ini_file($filePath);
                    if (!empty($fileConfig)) {
                        foreach ($fileConfig as $key => $value) {
                            $key = strtoupper(trim($key));
                            putenv($this->envPrefix . "_" . $key . "=" . $value);
                            $loadedConfig[$key] = $value;
                        }
                    }
                }
            }

            $reflection = new ReflectionClass($target);

            foreach ($reflection->getProperties() as $property) {
                $attributes = $property->getAttributes(ConfigAttribute::class);
                foreach ($attributes as $attribute) {
                    /* @var ConfigAttribute $attr */
                    $attr = $attribute->newInstance();
                    $attrName = strtoupper($attr->name);
                    $attrVal = null;
                    if (array_key_exists($attrName, $loadedConfig)) {
                        $attrVal = $loadedConfig[$attrName];
                    }
                    if (is_null($attrVal) || $attrVal === '') {
                        $attrVal = $attr->defaultValue;
                    }
                    if (!is_null($attrVal)) {
                        $property->setValue($target, $attrVal);
                        break;
                    }
                }
            }
            $reflection->getProperty("loaded")->setValue($target, true);
        }
    }
}
