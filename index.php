<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once "vendor/autoload.php";

/*
 * The framework reads the selected language from the signed "lang" cookie.
 * Normalize it before the application boots so legacy plain-text cookies are
 * migrated before the framework validates them.
 */
function surmeyLanguage(): string
{
    $cookieConfig = require __DIR__ . '/app/config/cookie.php';
    $cookieValue = $_COOKIE['lang'] ?? null;
    $language = null;

    if (is_string($cookieValue)) {
        [$value, $signature] = array_pad(explode('--', $cookieValue, 2), 2, null);
        $isSigned = is_string($signature)
            && hash_equals(md5($value . $cookieConfig['key']), $signature);

        if ($isSigned && in_array($value, ['tr_TR', 'en_US'], true)) {
            return $value;
        }

        // Migrate the plain cookie created by the previous implementation.
        if (in_array($cookieValue, ['tr_TR', 'en_US'], true)) {
            $language = $cookieValue;
        }
    }

    if ($language === null) {
        $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $preferredLanguage = strtolower(trim(explode(',', $acceptLanguage)[0] ?? ''));
        $language = str_starts_with($preferredLanguage, 'tr') ? 'tr_TR' : 'en_US';
    }

    $signedCookie = $language . '--' . md5($language . $cookieConfig['key']);
    $_COOKIE['lang'] = $signedCookie;
    setcookie(
        'lang',
        $signedCookie,
        time() + (365 * 24 * 60 * 60),
        '/',
        '',
        false,
        true
    );

    return $language;
}

function surmeyHtmlLanguage(): string
{
    return surmeyLanguage() === 'tr_TR' ? 'tr' : 'en';
}

surmeyLanguage();

$elapsedTime = \Core\App::weakup(__DIR__);
#stackMessages("Page loaded in: $elapsedTime s", true);
