<?php

namespace Laltu\LaravelMaker\Support;

class DeleteRequestGenerator
{
    private $id;
    private $routeName;
    private $config;
    private $onSuccessCallback;
    private array $headers;
    private array $queryParams;
    private array $requestData;

    public function __construct($id, $routeName, $config, $onSuccessCallback) {
        $this->id = $id;
        $this->routeName = $routeName;
        $this->config = $config;
        $this->onSuccessCallback = $onSuccessCallback;
        $this->headers = [];
        $this->queryParams = [];
        $this->requestData = [];
    }

    public function setHeader($name, $value): void
    {
        $this->headers[$name] = $value;
    }

    public function getHeader($name) {
        return $this->headers[$name] ?? null;
    }

    public function setQueryParam($name, $value): void
    {
        $this->queryParams[$name] = $value;
    }

    public function getQueryParam($name) {
        return $this->queryParams[$name] ?? null;
    }

    public function setRequestData($data): void
    {
        $this->requestData = $data;
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }

    private function generateDeleteRequestURL(): string
    {
        // Replace this with your route generation logic
        $url = route($this->routeName, $this->id);

        // Add query parameters to the URL
        if (!empty($this->queryParams)) {
            $url .= '?' . http_build_query($this->queryParams);
        }

        return $url;
    }

    public function generateDeleteRequestCode(): string
    {
        $url = $this->generateDeleteRequestURL();
        $config = $this->config;
        $onSuccessCallback = $this->onSuccessCallback;

        $headers = json_encode($this->headers);
        $requestData = json_encode($this->requestData);

        return "
            const headers = $headers;
            const requestData = $requestData;
            router.delete('$url', { ...$config, headers, data: requestData }, {
                onSuccess: $onSuccessCallback
            });
        ";
    }

    public function handleResponse($response) {
        // Add logic to handle the response here
    }
}