<?php

namespace Base\Responses;

class ApiResponse
{
    public static function deleted()
    {
        return \response()->noContent(204);
    }
}
