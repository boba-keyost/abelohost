<?php

namespace Router;

class ServerInfo
{
    protected string $sessionId;
    protected string $remoteIp;
    protected string $httpHost;
    protected string $userAgent;

    protected string $visitorId;

    protected function __construct(string $sessionId, string $remoteIp, string $httpHost, string $userAgent)
    {
        $this->sessionId = $sessionId;

        $this->remoteIp = $remoteIp;
        $this->httpHost = $httpHost;
        $this->userAgent = $userAgent;

        $this->visitorId = hash(
            "sha256",
            $this->toJson()
        );
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

    public static function fromServerInfo(string $sessionId = "", array $serverInfo = []): static
    {
        $remoteIp = $serverInfo["X_FORWARDED_FOR"] ?? $serverInfo['REMOTE_ADDR'] ?? '';
        $httpHost = $serverInfo["X_FORWARDED_HOST"] ?? $serverInfo['HTTP_HOST'] ?? '';
        $userAgent = $serverInfo['HTTP_USER_AGENT'] ?? '';

        return new static($sessionId, $remoteIp, $httpHost, $userAgent);
    }

    public function getVisitorId(): string
    {
        return $this->visitorId;
    }

    public function __toString(): string
    {
        return $this->toJson(JSON_PRETTY_PRINT);
    }

    public function toArray(): array
    {
        return [
            "remote_ip" => $this->getRemoteIp(),
            "http_host" => $this->getHttpHost(),
            "user_agent" => $this->getUserAgent(),
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
