<?php

$frameworkFile = dirname(__DIR__) . '/vendor/sdclowen/sdphp/src/App.php';

if (!is_file($frameworkFile) || !is_readable($frameworkFile) || !is_writable($frameworkFile)) {
    fwrite(STDERR, "sdphp App.php not found or is not writable; language loader patch skipped.\n");
    exit(0);
}

$contents = file_get_contents($frameworkFile);
$from = '$GLOBALS["APP_LANGUAGE"] = include_once LANG_DIR . "/$lang.php";';
$fromPatched = '$GLOBALS["APP_LANGUAGE"] = include LANG_DIR . "/$lang.php";';
$to = '$GLOBALS["APP_LANGUAGE"] = require LANG_DIR . "/$lang.php";';
$languageFrom = '$lang = $cookie->get("lang");';
$languageTo = '$lang = $cookie->get("lang");\n            $lang = explode("--", $lang, 2)[0];';

if (strpos($contents, $from) !== false) {
    $contents = str_replace($from, $to, $contents);
} elseif (strpos($contents, $fromPatched) !== false) {
    $contents = str_replace($fromPatched, $to, $contents);
} elseif (strpos($contents, $to) === false) {
    fwrite(STDERR, "sdphp language loader signature was not found; patch skipped.\n");
    exit(0);
}

if (strpos($contents, $languageTo) === false && strpos($contents, $languageFrom) !== false) {
    $contents = str_replace($languageFrom, $languageTo, $contents);
}

file_put_contents($frameworkFile, $contents);
fwrite(STDOUT, "sdphp language loader patched.\n");
