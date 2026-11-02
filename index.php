<?php
require_once "vendor/autoload.php";

/*
 * The framework reads the selected language from the "lang" cookie.  Create
 * the cookie before the application boots; existing cookies are left to the
 * framework's cookie security layer.
 */
function surmeyLanguage(): string
{
    if (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['tr_TR', 'en_US'], true)) {
        return $_COOKIE['lang'];
    }

    $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    $preferredLanguage = strtolower(trim(explode(',', $acceptLanguage)[0] ?? ''));
    $language = str_starts_with($preferredLanguage, 'tr') ? 'tr_TR' : 'en_US';

    setcookie('lang', $language, [
        'expires' => time() + (365 * 24 * 60 * 60),
        'path' => '/',
        'samesite' => 'Lax'
    ]);

    return $language;
}

function surmeyHtmlLanguage(): string
{
    if (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['tr_TR', 'en_US'], true)) {
        return $_COOKIE['lang'] === 'tr_TR' ? 'tr' : 'en';
    }

    $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    $preferredLanguage = strtolower(trim(explode(',', $acceptLanguage)[0] ?? ''));

    return str_starts_with($preferredLanguage, 'tr') ? 'tr' : 'en';
}

surmeyLanguage();

$elapsedTime = \Core\App::weakup(__DIR__);
#stackMessages("Page loaded in: $elapsedTime s", true);
