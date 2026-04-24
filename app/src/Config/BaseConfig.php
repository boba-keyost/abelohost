<?php

namespace Config;

use Exception;
use ReflectionObject;

abstract class BaseConfig
{
    protected bool $loaded = false;
    protected ConfigLoader $configLoader;

    protected static ?BaseConfig $default = null;

    /**
     * @throws Exception
     */
    public function __get($name)
    {
        $ref = new ReflectionObject($this);
        if (!$ref->hasProperty($name)) {
            throw new Error(Error::ERROR_PROPERTY_UNKNOWN);
        }
        $prop = $ref->getProperty($name);
        $attributes = $prop->getAttributes(ConfigAttribute::class);
        if (empty($attributes)) {
            throw new Error(Error::ERROR_PROPERTY_UNKNOWN);
        }
        $this->load();
        return $prop->getValue($this);
    }

    /**
     * @throws Exception
     */
    public function __set($name, $value)
    {
        throw new Error(Error::ERROR_PROPERTY_SET_RESTRICTED);
    }

    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    public function toArray(): array
    {
        $reflection = new ReflectionObject($this);

        $properties = $reflection->getProperties();
        $result = [];
        foreach ($properties as $property) {
            $attributes = $property->getAttributes(ConfigAttribute::class);
            if (!empty($attributes)) {
                /* @var ConfigAttribute $attr */
                $attr = $attributes[0]->newInstance();
                $result[$attr->name] = $property->getValue($this);
            }
        }

        return $result;
    }

    public function __construct(?ConfigLoader $configLoader = null)
    {
        if (is_null($configLoader)) {
            $configLoader = ConfigLoader::defaultLoader();
        }
        $this->configLoader = $configLoader;
    }

    protected function load(): void
    {
        $this->configLoader->load($this);
    }

    public static function defaultConfig(): BaseConfig
    {
        if (is_null(self::$default)) {
            self::$default = new static(ConfigLoader::defaultLoader());
        }
        return self::$default;
    }
}
