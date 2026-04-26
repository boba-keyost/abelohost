<?php

namespace Features\Assets;

use App\Error;

class Assets
{
    protected static ?Assets $instance = null;
    protected string $assetsDir;

    public static function getInstance(): static
    {
        if (is_null(self::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @throws Error
     */
    protected function __construct()
    {
        $this->assetsCache = [];
        $this->assetsDir = realpath(__DIR__ . "/../../../assets/");
        if (!is_dir($this->assetsDir)) {
            throw Error::notFound("Assets directory does not exist");
        }
    }

    protected function __clone()
    {
    }

    protected array $assetsCache = [];

    protected function key(AssetType $type, string $file): string
    {
        return $type->value . "-" . $file;
    }

    protected function scssFileName(string $file): string
    {
        return $file . ".scss";
    }

    /**
     * @throws Error
     */
    public function loadAsset(AssetType $type, string $file): Asset
    {
        $key = $this->key($type, $file);
        if (empty($this->assetsCache[$key])) {
            $this->assetsCache[$key] = $this->new($type, $file)->load();
        }

        return $this->assetsCache[$key];
    }

    /**
     * @throws Error
     */
    public function loadSCSSAsset(string $file): Asset
    {
        return $this->loadAsset(AssetType::Styles, $this->scssFileName($file));
    }

    public function new(AssetType $type, string $file): Asset
    {
        return new Asset($type, $file);
    }

    public function newSCSS(string $file): Asset
    {
        return $this->new(AssetType::Styles, $this->scssFileName($file));
    }

    public function getAssetDirPath(AssetType $type): string|false
    {
        return realpath(sprintf(
            $this->assetsDir . "/%s",
            $this->escape($type->value),
        ));
    }

    public function getAssetPath(AssetType $type, string $file): string|false
    {
        $key = $this->key($type, $file);
        if (array_key_exists($key, $this->assetsCache)) {
            return $this->assetsCache[$key]->getFilePath();
        }
        return realpath(sprintf(
            $this->assetsDir . "/%s/%s",
            $this->escape($type->value),
            $this->escape($file)
        ));
    }

    public function isAssetExists(AssetType $type, string $file): bool
    {
        $key = $this->key($type, $file);
        if (array_key_exists($key, $this->assetsCache)) {
            return true;
        }
        $path = $this->getAssetPath($type, $file);

        return $path && is_file($path);
    }

    public function isSCSSAssetExists(string $file): bool
    {
        return $this->isAssetExists(AssetType::Styles, $this->scssFileName($file));
    }

    public function escape(string $path): string
    {
        return str_replace("../", "/", $path);
    }
}
