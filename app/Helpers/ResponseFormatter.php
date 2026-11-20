<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use SimpleXMLElement;

class ResponseFormatter
{
    public static function make(Request $request, array $payload, int $status = 200)
    {
        $payload = json_decode(json_encode($payload), true);

        if (self::wantsXml($request)) {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response/>');
            self::toXml($payload, $xml);

            return response($xml->asXML(), $status, ['Content-Type' => 'application/xml']);
        }

        return response()->json($payload, $status);
    }

    private static function wantsXml(Request $request): bool
    {
        $format = strtolower((string) $request->query('format', ''));

        if ($format === 'xml') return true;
        if ($format === 'json') return false;

        return str_contains(strtolower((string) $request->header('Accept', '')), 'xml');
    }

    private static function toXml(array $data, SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? 'item' : $key;

            if (is_array($value)) {
                self::toXml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, htmlspecialchars((string) $value));
            }
        }
    }
}