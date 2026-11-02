<!DOCTYPE html>
<html lang="{surmeyHtmlLanguage()}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/favicon.png" sizes="16x16 32x32" type="image/png">
    <title> {$title} </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/2.3.0/alpine.js" integrity="sha512-nIwdJlD5/vHj23CbO2iHCXtsqzdTTx3e3uAmpTm4x2Y8xCIFyWu4cSIV8GaGe2UNVq86/1h9EgUZy7tn243qdA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {acss("app")|noescape}
    {njs("jquery/dist/jquery.min")|noescape}
    {njs("sdpower/sdeasy")|noescape}
    {ajs("app")|noescape}
</head>

<body class="bg-slate-200/75 text-slate-900 dark:bg-slate-900 dark:text-slate-50 p-2">

    <div class="alert-success text-center max-w-2xl mx-auto my-5 p-5 overflow-hidden rounded-lg shadow-xl ring-1 ring-gray-900/5">
        Teşekkürler, {$title}<br>
        <i class="text-gray-700 dark:text-gray-200">Dilerseniz pencereyi kapatabilirsiniz.</i>
    </div>
</body>

</html>
