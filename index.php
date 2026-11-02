<?php
require_once "vendor/autoload.php";

/*
 * The framework reads the selected language from the "lang" cookie.  Set it
 * before the application boots so translations are available to controllers
 * and templates during the very first request as well.
 */
function surmeyLanguage(): string
{
    if (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['tr_TR', 'en_US'], true)) {
        return $_COOKIE['lang'];
    }

    $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    $preferredLanguage = strtolower(trim(explode(',', $acceptLanguage)[0] ?? ''));
    $language = str_starts_with($preferredLanguage, 'tr') ? 'tr_TR' : 'en_US';

    $_COOKIE['lang'] = $language;
    setcookie('lang', $language, [
        'expires' => time() + (365 * 24 * 60 * 60),
        'path' => '/',
        'samesite' => 'Lax'
    ]);

    return $language;
}

function surmeyHtmlLanguage(): string
{
    return ($_COOKIE['lang'] ?? 'en_US') === 'tr_TR' ? 'tr' : 'en';
}

surmeyLanguage();

$elapsedTime = \Core\App::weakup(__DIR__);
#stackMessages("Page loaded in: $elapsedTime s", true);
