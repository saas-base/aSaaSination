<?php

namespace Base\Contracts\Abstracts;

use Illuminate\Foundation\Testing\TestResponse;

abstract class HttpTest extends Base\Contracts\Abstracts\TestCase
{
    protected function decodeHttpResponse($content, $unwrap = true)
    {
        if ($content instanceof TestResponse) {
            $content = $content->content();
        }

        if ($unwrap) {
            return json_decode($content, true)['data'] ?? json_decode($content, true);
        }

        return json_decode($content, true);
    }

    protected function http(string $method, string $route, array $payload = [], array $headers = []) : Base\Contracts\Abstracts\TestResponse
    {
        if (! in_array($method, ['GET', 'POST', 'PATCH', 'DELETE', 'PUT'])) {
            throw new \Exception('Invalid Http Method');
        }

        return new Base\Contracts\Abstracts\TestResponse($this->json($method, env('API_URL').$route, $payload, $headers)->baseResponse);
    }
}
