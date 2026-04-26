<?php

namespace Features\Assets;

use App\Error;

class Asset
{
    protected AssetType $type;
    protected string $fileName;
    protected ?string $filePath = null;
    protected ?string $content = null;
    protected ?array $stats = null;

    public function __construct(AssetType $type, string $fileName)
    {
        $this->type = $type;
        $this->fileName = $fileName;
    }

    /**
     * @throws Error
     */
    public function load(): static
    {
        $this->getFilePath();

        return $this;
    }

    /**
     * @throws Error
     */
    public function getFilePath(): string
    {
        if (is_null($this->filePath)) {
            if (!Assets::getInstance()->isAssetExists($this->type, $this->fileName)) {
                throw Error::notFound(sprintf("Asset %s/%s stats does not exist", $this->type->value, $this->fileName));
            }
            $this->filePath = Assets::getInstance()->getAssetPath($this->type, $this->fileName);
        }
        return $this->filePath;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @throws Error
     */
    public function getContent(): string
    {
        if (is_null($this->content)) {
            $this->content = file_get_contents($this->getFilePath());
        }
        return $this->content;
    }

    /**
     * @throws Error
     */
    protected function getStat(string $key): int
    {
        if (is_null($this->stats)) {
            $this->stats = stat($this->getFilePath());
        }
        return $this->stats[$key] ?? 0;
    }

    /**
     * @throws Error
     */
    public function getCTime(): int
    {
        return $this->getStat('ctime');
    }

    /**
     * @throws Error
     */
    public function getMTime(): int
    {
        return $this->getStat('mtime');
    }

    /**
     * @throws Error
     */
    public function getATime(): int
    {
        return $this->getStat('atime');
    }

    /**
     * @throws Error
     */
    public function getSize(): int
    {
        return $this->getStat('size');
    }
}
