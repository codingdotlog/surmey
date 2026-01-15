<!DOCTYPE html>
<html class="h-full" lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/favicon.png" sizes="16x16 32x32" type="image/png">
    <title>{$title}</title>
    {acss("app")|noescape}
    {njs("jquery/dist/jquery.min")|noescape}
    {ajs("jquery.inputmask.min")|noescape}
    {njs("sdpower/sdeasy")|noescape}
    <script>
        $(function () {
            $(":input[name=phone]").inputmask({ "mask": "(999) 999 99 99" });

            // Tabs: phone / email
            function setTab(tab) {
                const $phoneTab = $("#tab-phone");
                const $emailTab = $("#tab-email");
                const $phonePanel = $("#panel-phone");
                const $emailPanel = $("#panel-email");

                if (tab === "email") {
                    $emailTab.addClass("bg-blue-700 text-white").removeClass("bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-200");
                    $phoneTab.addClass("bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-200").removeClass("bg-blue-700 text-white");
                    $emailPanel.removeClass("hidden");
                    $phonePanel.addClass("hidden");
                    $("input[name=channel]").val("email");
                    $("input[name=phone]").val("");
                    $("input[name=email]").focus();
                } else {
                    $phoneTab.addClass("bg-blue-700 text-white").removeClass("bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-200");
                    $emailTab.addClass("bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-200").removeClass("bg-blue-700 text-white");
                    $phonePanel.removeClass("hidden");
                    $emailPanel.addClass("hidden");
                    $("input[name=channel]").val("phone");
                    $("input[name=email]").val("");
                    $("input[name=phone]").focus();
                }
            }

            $("#tab-phone").on("click", function(){ setTab("phone"); });
            $("#tab-email").on("click", function(){ setTab("email"); });

            // default tab
            setTab("phone");
        })
    </script>
</head>

<body class="flex flex-wrap min-h-screen w-full content-center justify-center bg-gray-200 dark:bg-slate-700 dark:text-gray-200 py-10 px-3">
    <div class="flex shadow-md relative overflow-hidden rounded-xl">
        <div class="message absolute bottom-0 w-4/5 ml-5"></div>
        <div class="flex flex-wrap content-center justify-center bg-white dark:bg-slate-900" style="width: 24rem; height: 32rem;">
            <div width="192" class="text-black dark:text-white mb-14 mx-auto min-[980px]:hidden"> 
                {include '..\..\..\public\images\logo.svg'}
            </div>
            <div class="w-72">

                <h1 class="text-2xl font-semibold">{lang("participate.welcome")}</h1>
                <small class="text-gray-400">{$survey->title}</small>
                <i class="text-gray-300 font-thin text-xs">{lang("participate.choose.method")}</i>
                <form role="form" class="space-y-6" action="/participate/verify-step1" method="post" data-content=".message">
                    {csrf()|noescape}
                    <input type="hidden" name="channel" value="phone" />

                    <div class="flex rounded-lg overflow-hidden border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
                        <button type="button" id="tab-phone" class="w-1/2 px-3 py-2 text-sm font-semibold transition-colors">{lang("type.phone")}</button>
                        <button type="button" id="tab-email" class="w-1/2 px-3 py-2 text-sm font-semibold transition-colors">{lang("type.email")}</button>
                    </div>

                    <div id="panel-phone" class="mb-3">
                        <label class="mb-2 block text-xs font-semibold">{lang("type.phone")}</label>
                        <input type="text" name="phone" placeholder="(555) 555 55 55" class="transition-colors transition-duration-200 block w-full rounded-md border border-gray-300 focus:border-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-700 p-2 text-gray-700 dark:text-gray-200 dark:border-slate-600 dark:bg-gray-900" />
                        <div class="text-xs text-gray-400 mt-2">{lang("participate.sms.hint")}</div>
                    </div>

                    <div id="panel-email" class="mb-3 hidden">
                        <label class="mb-2 block text-xs font-semibold">{lang("type.email")}</label>
                        <input type="text" name="email" placeholder="{lang("participate.email.placeholder")}" class="transition-colors transition-duration-200 block w-full rounded-md border border-gray-300 focus:border-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-700 p-2 text-gray-700 dark:text-gray-200 dark:border-slate-600 dark:bg-gray-900" />
                        <div class="text-xs text-gray-400 mt-2">{lang("participate.email.hint")}</div>
                    </div>

                    <div class="mb-3">
                        <button class="mb-1.5 block w-full text-center text-white bg-blue-700 hover:bg-blue-900 transition-colors transition-duration-200 px-2 py-1.5 rounded-md">{lang("participate.continue")}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white text-black flex flex-wrap content-center justify-center max-[980px]:hidden" style="width: 24rem; height: 32rem;">
            {include '..\..\..\public\images\logo.svg'}
        </div>

    </div>

    <div class="mt-3 w-full">
        <p class="text-center text-gray-500 dark:text-gray-400">Copyright © {date("Y")} All rights <a href="https://github.com/SDClowen">reserved.</a>
        </p>
    </div>
</body>

</html>