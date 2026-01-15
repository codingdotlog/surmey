<!DOCTYPE html>
<html class="h-full" lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/favicon.png" sizes="16x16 32x32" type="image/png">
    <title>Surmey - {$title}</title>
    {acss("app")|noescape}
    {njs("jquery/dist/jquery.min")|noescape}
    {njs("sdpower/sdeasy")|noescape}

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>
</head>

<body class="fixed inset-0 w-full h-full flex bg-gray-200 dark:bg-slate-700 dark:text-gray-200 items-center justify-center min-[980px]:px-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 max-[980px]:mx-4" x-data="app()">
        <div class="font-thin px-2 pb-4 text-lg">{lang("participate.pin.enter")}</div>
        <div class="font-thin px-2 pb-4 text-center text-gray-500">
            {if isset($pin->channel) && $pin->channel === "email"}
                <b>{$pin->maskedEmail}</b><br>{lang("participate.pin.sent.email")}
            {else}
                <b>+90 (***) *** {$pin->phoneLastNum4}</b><br>{lang("participate.pin.sent.sms")}
            {/if}
        </div>
        <div class="flex">
            <template x-for="(l,i) in pinlength" :key="`codefield_${ i }`">
                <input :autofocus="i == 0" :id="`codefield_${ i }`" class="dark:bg-gray-950 dark:border-gray-700 focus:border-blue-300 focus:outline-blue-500 h-16 w-12 border mx-2 rounded-xl text-center font-thin text-3xl" value="" maxlength="1" max="9" min="0" inputmode="numeric" @keyup="stepForward(i)" @keydown.backspace="stepBack(i)" @focus="resetValue(i)"/>
            </template>
            {csrf()|noescape}
            <input type="hidden" name="token" value="{$pin->token}">
        </div>
        <h1 class="text-center m-8 font-extralight text-slate-800 dark:text-gray-200 text-7xl"> </h1>
    </div>
</body>

<script type="text/javascript">
    $(()=>{
        $('#codefield_0').focus()
    })

    function app() {
        return {
            pinlength: 6,
            resetValue(i) {
                for (x = 0; x < this.pinlength; x++) {
                    if (x >= i) document.getElementById(`codefield_${ x }`).value = ''
                }
            },
            resetAll(){
                for (x = 0; x < this.pinlength; x++) {
                    document.getElementById(`codefield_${ x }`).value = ''
                }
            },
            stepForward(i) {
                const dom = document.getElementById(`codefield_${ i }`)
                if(!parseInt(dom.value))
                {
                    dom.value = ''
                    return
                }

                if (dom.value && i != this.pinlength - 1) {
                    document.getElementById(`codefield_${ i + 1 }`).focus()
                    document.getElementById(`codefield_${ i + 1 }`).value = ''
                }
                this.checkPin()
            },
            stepBack(i) {
                if (document.getElementById(`codefield_${ i - 1 }`).value && i != 0) {
                    document.getElementById(`codefield_${ i - 1 }`).focus()
                    document.getElementById(`codefield_${ i - 1 }`).value = ''
                }
            },
            checkPin() {
                let code = ''
                for (i = 0; i < this.pinlength; i++) {
                    code = code + document.getElementById(`codefield_${ i }`).value
                }
                if (code.length == this.pinlength) {
                    this.validatePin(code)
                }
            },
            validatePin(code) {
                const token = document.querySelector("input[name='token']").value
                const csrf = document.querySelector("input[name='csrf']").value

                $.ajax({
                    url: "/participate/pin",
                    method: "post",
                    dataType: "json",
                    data: {
                        pin: code,
                        token: token,
                        csrf: csrf
                    },
                    success: function(result)
                    {
                        if(result.type != "success")
                        {
                            alert(result.message)
                            return
                        }
                        
                        if(result.redirect)
                            window.location.href = result.redirect
                        else 
                            window.location.href = "/participate"
                    }
                })
            }
        }
    }
    
</script>

</html>