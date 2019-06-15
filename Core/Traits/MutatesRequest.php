<?php

namespace Core\Traits;

trait MutatesRequest
{
    public function injectUserId($request): array
    {
        return array_merge($request->toArray(), ['user_id' => get_authenticated_user_id()]);
    }
}
