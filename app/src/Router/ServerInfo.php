<?php

namespace Router;

use JsonSerializable;

class ServerInfo implements JsonSerializable
{
    protected string $sessionId;
    protected string $contentType;
    protected string $remoteIp;
    protected string $httpHost;
    protected string $userAgent;

    protected string $visitorId;

    protected function __construct(
        string $sessionId,
        string $contentType,
        string $remoteIp,
        string $httpHost,
        string $userAgent
    ) {
        $this->sessionId = $sessionId;

        $this->contentType = $contentType;

        $this->remoteIp = $remoteIp;
        $this->httpHost = $httpHost;
        $this->userAgent = $userAgent;

        $this->visitorId = hash(
            "sha256",
            json_encode($this),
        );
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function isJSON(): bool
    {
        return $this->getContentType() === "application/json";
    }

    public function getRemoteIp(): string
    {
        return $this->remoteIp;
    }

    public function getHttpHost(): string
    {
        return $this->httpHost;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function getVisitorId(): string
    {
        return $this->visitorId;
    }

    public function __toString(): string
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }

    public function toArray(): array
    {
        return [
            "is_json" => $this->isJSON(),
            "remote_ip" => $this->getRemoteIp(),
            "http_host" => $this->getHttpHost(),
            "user_agent" => $this->getUserAgent(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public static function fromServerInfo(string $sessionId = "", array $serverInfo = []): static
    {
        $remoteIp = $serverInfo["X_FORWARDED_FOR"] ?? $serverInfo['REMOTE_ADDR'] ?? '';
        $httpHost = $serverInfo["X_FORWARDED_HOST"] ?? $serverInfo['HTTP_HOST'] ?? '';
        $userAgent = $serverInfo['HTTP_USER_AGENT'] ?? '';
        $contentType = $serverInfo["CONTENT_TYPE"] ?? '';

        return new static($sessionId, $contentType, $remoteIp, $httpHost, $userAgent);
    }
}
