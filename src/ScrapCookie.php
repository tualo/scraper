<?php

namespace Tualo\Office\Scraper;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\ExtJSCompiler\ICompiler;
use Tualo\Office\ExtJSCompiler\CompilerHelper;

class ScrapCookie
{


    public static function scrapLocation(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $headerLines = explode("\n", str_replace("\r", "", $header));
        preg_match_all('/^Location:\s*([^;]*)/mi', $header, $matches);
        $location = "";
        foreach ($headerLines as $line) {
            if (preg_match('/^location:\s*([^;]*)/mi', $line, $match)) {
                $location = trim($match[1]);
            } else {
                continue;
            }
        }
        curl_close($ch);
        return $location;
    }

    public static function scrapDocument(string $url, string $cookie = '', $post = NULL, $method = 'POST')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($method == 'PUT') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($post)));
        }
        if (!is_null($post)) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        // cokkie setzen, wenn vorhanden
        if ($cookie != '') {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        // curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }

    public static function scrapPutDocument(string $url, string $cookie = '', $put = NULL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!is_null($put)) curl_setopt($ch, CURLOPT_POSTFIELDS, $put);
        // cokkie setzen, wenn vorhanden
        if ($cookie != '') {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        // curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }


    public static function scrapCookie(string $url, $post = NULL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if (!is_null($post)) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $headerLines = explode("\n", str_replace("\r", "", $header));
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $matches);
        $cookies = array();
        foreach ($headerLines as $line) {
            if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $line, $match)) {
                $cookies[] = $line;
            } else {
                continue;
            }
        }
        curl_close($ch);
        return $cookies;
    }
}
