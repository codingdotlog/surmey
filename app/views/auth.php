<!DOCTYPE html>
<html class="h-full" lang="{surmeyHtmlLanguage()}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/favicon.png" sizes="16x16 32x32" type="image/png">
    <title>Surmey - {$title}</title>
    {acss("app")|noescape}
    {njs("jquery/dist/jquery.min")|noescape}
    {njs("sdpower/sdeasy")|noescape}
</head>

<body class="flex flex-wrap min-h-screen w-full content-center justify-center bg-gray-200 dark:bg-slate-700 dark:text-gray-200 py-10">
<div class="flex shadow-md relative overflow-hidden rounded-xl">
        <div class="message absolute bottom-0 w-4/5 ml-5"></div>
        <div class="flex flex-wrap content-center justify-center bg-white dark:bg-slate-900" style="width: 24rem; height: 32rem;">
            <div width="192" class="text-black dark:text-white mb-14 mx-auto min-[980px]:hidden"> 
                {include '..\..\public\images\logo.svg'}
            </div>
            <div class="w-72">
                <h1 class="text-xl font-semibold">Hoşgeldiniz</h1>
                <small class="text-gray-400">Lütfen Giriş Bilgilerinizi Girin</small>

                <form role="form" class="space-y-6" action="/auth" method="post" data-content=".message">
                    {csrf()|noescape}
                    <div class="mb-3">
                        <label class="mb-2 block text-xs font-semibold">{lang("username.or.email")}</label>
                        <input type="text" name="userNameOrEmail" placeholder="Enter your username or email" class="transition-colors transition-duration-200 block w-full rounded-md border border-gray-300 focus:border-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-700 p-2 text-gray-700 dark:text-gray-200 dark:border-slate-600 dark:bg-gray-900" />
                    </div>

                    <div class="mb-3">
                        <label class="mb-2 block text-xs font-semibold">{lang("password")}</label>
                        <input type="password" name="password" placeholder="•••••••" class="transition-colors transition-duration-200 block w-full rounded-md border border-gray-300 focus:border-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-700 p-2 text-gray-700 dark:text-gray-200 dark:border-slate-600 dark:bg-gray-900" />
                    </div>

                    <div class="mb-3 flex flex-wrap content-center">
                        <input id="remember" type="checkbox" class="mr-1 checked:bg-purple-700" /> <label for="remember" class="mr-auto text-xs font-semibold">Remember for 30 days</label>
                        <!--<a href="#" class="text-xs font-semibold text-purple-700">Forgot password?</a>-->
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="mb-1.5 block w-full text-center text-white bg-blue-600 hover:bg-blue-800 transition-colors transition-duration-200 px-2 py-1.5 rounded-md">Sign in</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white text-black flex flex-wrap content-center justify-center max-[980px]:hidden" style="width: 24rem; height: 32rem;">
            <img id="loginImage" class="w-full h-full bg-center bg-no-repeat bg-cover rounded-r-md" src="">
        </div>

    </div>

    <div class="mt-3 w-full">
        <p class="text-center text-gray-500 dark:text-gray-400">Copyright ©
            {date("Y")} <a href="https://github.com/SDClowen">All rights reserved.</a>
        </p>
    </div>


    <script>
        const images = [
            "https://i.imgur.com/IefNfih.jpeg",
            "https://i.imgur.com/9l1A4OS.jpeg",
            "https://i.imgur.com/WqEWifN.jpeg"
        ]

        const domImage = document.querySelectorAll("#loginImage");
        if (domImage) {
            domImage.forEach((element, key) =>{
                element.src = images[Math.floor(Math.random() * 3)]
            })
        }

    </script>
</body>

</html>
