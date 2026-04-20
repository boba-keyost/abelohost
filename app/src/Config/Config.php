<?php
namespace Config;

use ReflectionClass;
use ReflectionObject;

class Config{
    #[ConfigAttribute(ConfigAttribute::DB_HOST, "mysql")]
    protected string $dbHost {
        get {
            $this->load();
            return $this->dbHost;
        }
    }
    #[ConfigAttribute(ConfigAttribute::DB_USER, "abelohost")]
    protected string $dbUser{
        get {
            $this->load();
            return $this->dbUser;
        }
    }
    #[ConfigAttribute(ConfigAttribute::DB_PASSWORD, "")]
    protected string $dbPassword{
        get {
            $this->load();
            return $this->dbPassword;
        }
    }
    #[ConfigAttribute(ConfigAttribute::DB_NAME, "abelohost")]
    protected string $dbName{
        get {
            $this->load();
            return $this->dbName;
        }
    }

    protected bool $loaded = false;
    protected string | null $env = null;

    protected static array $instances;
    protected static string $rootDirectory = __DIR__ . DIRECTORY_SEPARATOR . "app";
    protected static string | null $environment = null;
    protected static string $envPrefix = "ABELOHOST";

    static public function setEnvironment(string | null $environment): void {
        static::$environment = $environment;
    }

    static public function setRootDirectory(string $rootDirectory): void {
        static::$rootDirectory = $rootDirectory;
    }

    public static function setPrefix(string $prefix): void
    {
        static::$envPrefix = $prefix;
    }

    static public function getInstance(): Config
    {
        if (empty(static::$instances[static::$environment])) {
            static::$instances[static::$environment] = new Config(static::$environment);
        }
        return static::$instances[static::$environment];
    }

    protected function __clone() {}

    protected function __construct(string | null $env = null) {
        if (is_null($env)) {
            $env = getenv("ENV");
        }
        if (!is_string($env) || $env === "") {
            $env = "development";
        }
        $this->env = $env;
    }

    protected function load(): void {
        if (!$this->loaded) {
            $filesToLoad = [
                ".env",
                ".env." . $this->env,
            ];
            $filteredEnv = array_filter(
                getenv(),
                fn (string $key) => str_starts_with($key, static::$envPrefix . "_"),
                ARRAY_FILTER_USE_KEY
            );
            $loadedConfig = [];
            foreach ($filteredEnv as $envKey => $envValue) {
                $loadedConfig[substr($envKey, strlen(static::$envPrefix) + 1)] = $envValue;
            }
            foreach ($filesToLoad as $file) {
                $filePath = realpath(static::$rootDirectory . DIRECTORY_SEPARATOR . $file);
                if (file_exists($filePath)) {
                    $fileConfig = parse_ini_file($filePath);
                    if (!empty($fileConfig)) {
                        foreach ($fileConfig as $key => $value) {
                            $key = strtoupper(trim($key));
                            putenv(static::$envPrefix . "_" . $key . "=" . $value);
                            $loadedConfig[$key] = $value;
                        }
                    }
                }
            }

            $reflection = new ReflectionClass(static::class);

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
                        $property->setValue($this, $attrVal);
                        break;
                    }
                }
            }
            $this->loaded = true;
        }
    }

    public function toArray(): array
    {
        $this->load();
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
}
