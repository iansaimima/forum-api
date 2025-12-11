<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ConvertResponseToCamelCase
{
    /**
     * Handle an incoming request and convert JSON response keys to camelCase.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type');

        if ($contentType && str_contains($contentType, 'application/json')) {
            $content = $response->getContent();

            if ($content) {
                $data = json_decode($content, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $converted = $this->keysToCamel($data);
                    $response->setContent(json_encode($converted));
                    $response->headers->set('Content-Type', 'application/json');
                }
            }
        }

        return $response;
    }

    /**
     * Recursively convert array keys to camelCase.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function keysToCamel($value)
    {
        if (is_array($value)) {
            if ($this->isAssoc($value)) {
                $result = [];
                foreach ($value as $key => $val) {
                    $result[Str::camel($key)] = $this->keysToCamel($val);
                }
                return $result;
            }

            // sequential array
            return array_map([$this, 'keysToCamel'], $value);
        }

        return $value;
    }

    /**
     * Determine if an array is associative.
     */
    protected function isAssoc(array $arr): bool
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
