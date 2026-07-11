<!DOCTYPE html>
<html lang="tr" class="scroll-smooth md:scroll-auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/favicon.png" sizes="16x16 32x32" type="image/png">
    <title>
        {$title}
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.js"
        integrity="sha512-8IPRU0MPrge2KfSxkAtO8pIkaMzThW/MBSvPqcyVisSymLWC986buo27pKAt5mWXmt58dT6jIsw7h8NNugtRwg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {acss("app")|noescape}
    {njs("jquery/dist/jquery.min")|noescape}

    {njs("tinymce/tinymce.min")|noescape}
    {njs("@tinymce/tinymce-jquery/dist/tinymce-jquery.min")|noescape}

    {njs("sdpower/sdeasy")|noescape}
    {ajs("app")|noescape}
    {njs("preline/preline")|noescape}
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.HSStaticMethods.autoInit();
        });
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-blue-700" x-data="{ mobileMenuOpen: false, accountMenuOpen: false }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center min-w-0">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8" width="64px" height="64px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="matrix(1, 0, 0, 1, 0, 0)" stroke="#fff">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"
                                    stroke="#fff" stroke-width="0.048"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path opacity="0.6"
                                        d="M20.1004 6.93998C20.1004 7.47998 19.8104 7.96995 19.3504 8.21995L17.6104 9.15995L16.1304 9.94999L13.0604 11.61C12.7304 11.79 12.3704 11.88 12.0004 11.88C11.6304 11.88 11.2704 11.79 10.9404 11.61L4.65039 8.21995C4.19039 7.96995 3.90039 7.47998 3.90039 6.93998C3.90039 6.39998 4.19039 5.90995 4.65039 5.65995L6.62039 4.59996L8.1904 3.74998L10.9404 2.27C11.6004 1.91 12.4004 1.91 13.0604 2.27L19.3504 5.65995C19.8104 5.90995 20.1004 6.39998 20.1004 6.93998Z"
                                        fill="#292D32"></path>
                                    <path opacity="0.6"
                                        d="M9.9007 12.7899L4.05069 9.85989C3.60069 9.62989 3.0807 9.65989 2.6507 9.91989C2.2207 10.1799 1.9707 10.6399 1.9707 11.1399V16.6699C1.9707 17.6299 2.50069 18.4899 3.36069 18.9199L9.21069 21.8399C9.41069 21.9399 9.63071 21.9899 9.85071 21.9899C10.1107 21.9899 10.3707 21.9199 10.6007 21.7699C11.0307 21.5099 11.2807 21.0499 11.2807 20.5499V15.0199C11.2907 14.0799 10.7607 13.2199 9.9007 12.7899Z"
                                        fill="#192D32"></path>
                                    <path opacity="0.6"
                                        d="M22.0309 11.1502V16.6801C22.0309 17.6301 21.501 18.4901 20.641 18.9201L14.791 21.8501C14.591 21.9501 14.3709 22.0001 14.1509 22.0001C13.8909 22.0001 13.631 21.9302 13.391 21.7802C12.971 21.5202 12.7109 21.0601 12.7109 20.5601V15.0401C12.7109 14.0801 13.241 13.2201 14.101 12.7901L16.2509 11.7201L17.7509 10.9701L19.951 9.87013C20.401 9.64013 20.921 9.66012 21.351 9.93012C21.771 10.1901 22.0309 10.6502 22.0309 11.1502Z"
                                        fill="#292D32"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="hidden md:block">
                            <ul class="ml-6 lg:ml-10 flex items-center space-x-2">
                                <li><a class="transition-colors duration-300 transform text-gray-100 hover:bg-blue-900 hover:text-white rounded-md px-3 py-2 text-sm font-medium"
                                        href="/" live="true" data-content="#container" spinner="false">Dashboard</a></li>
                                <li><a class="transition-colors duration-300 transform text-gray-100 hover:bg-blue-900 hover:text-white rounded-md px-3 py-2 text-sm font-medium"
                                        href="/surveys" live="true" data-content="#container" spinner="false">Anketler</a></li>
                                <li><a class="transition-colors duration-300 transform text-gray-100 hover:bg-blue-900 hover:text-white rounded-md px-3 py-2 text-sm font-medium"
                                        href="/settings" live="true" data-content="#container" spinner="false">Ayarlar</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="hidden md:block">

                        <div class="relative inline-block">
                            <!-- Dropdown toggle button -->
                            <button @click="accountMenuOpen = !accountMenuOpen"
                                class="relative z-10 flex transition-colors duration-300 transform items-center p-2 text-sm text-gray-200 border-transparent rounded-md hover:bg-blue-900 focus:bg-blue-900 focus:border-blue-500 focus:ring-opacity-40 focus:ring-blue-300 focus:ring focus:outline-none">
                                <span class="mx-1">{$user->name}</span>
                                <svg class="w-5 h-5 mx-1" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 15.713L18.01 9.70299L16.597 8.28799L12 12.888L7.40399 8.28799L5.98999 9.70199L12 15.713Z"
                                        fill="currentColor"></path>
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="accountMenuOpen" @click.away="accountMenuOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="absolute right-0 z-20 w-56 py-2 mt-2 overflow-hidden origin-top-right bg-white rounded-md shadow-xl dark:bg-gray-800">
                                <div
                                    class="flex items-center p-3 -mt-2 text-sm text-gray-600 transition-colors duration-300 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <img class="flex-shrink-0 object-cover mx-1 rounded-full w-9 h-9"
                                        src="https://images.unsplash.com/photo-1523779917675-b6ed3a42a561?ixid=MnwxMjA3fDB8MHxzZWFyY2h8N3x8d29tYW4lMjBibHVlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=face&w=500&q=200"
                                        alt="jane avatar">
                                    <div class="mx-1">
                                        <h1 class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                            <pre>{$user->name}</pre>
                                        </h1>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{$user->email}</p>
                                    </div>
                                </div>

                                <hr class="border-gray-200 dark:border-gray-700 ">

                                <a href="/settings" live="true" data-content="#container"
                                    class="flex items-center m-1 rounded-md p-2 text-sm text-gray-600 capitalize transition-colors duration-300 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <svg class="w-5 h-5 mx-1" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13.8199 22H10.1799C9.71003 22 9.30347 21.673 9.20292 21.214L8.79592 19.33C8.25297 19.0921 7.73814 18.7946 7.26092 18.443L5.42392 19.028C4.97592 19.1709 4.48891 18.9823 4.25392 18.575L2.42992 15.424C2.19751 15.0165 2.27758 14.5025 2.62292 14.185L4.04792 12.885C3.98312 12.2961 3.98312 11.7019 4.04792 11.113L2.62292 9.816C2.27707 9.49837 2.19697 8.98372 2.42992 8.576L4.24992 5.423C4.48491 5.0157 4.97192 4.82714 5.41992 4.97L7.25692 5.555C7.50098 5.37416 7.75505 5.20722 8.01792 5.055C8.27026 4.91269 8.52995 4.78385 8.79592 4.669L9.20392 2.787C9.30399 2.32797 9.71011 2.00049 10.1799 2H13.8199C14.2897 2.00049 14.6958 2.32797 14.7959 2.787L15.2079 4.67C15.4887 4.79352 15.7622 4.93308 16.0269 5.088C16.2739 5.23081 16.5126 5.38739 16.7419 5.557L18.5799 4.972C19.0276 4.82967 19.514 5.01816 19.7489 5.425L21.5689 8.578C21.8013 8.98548 21.7213 9.49951 21.3759 9.817L19.9509 11.117C20.0157 11.7059 20.0157 12.3001 19.9509 12.889L21.3759 14.189C21.7213 14.5065 21.8013 15.0205 21.5689 15.428L19.7489 18.581C19.514 18.9878 19.0276 19.1763 18.5799 19.034L16.7419 18.449C16.5093 18.6203 16.2677 18.7789 16.0179 18.924C15.7557 19.0759 15.4853 19.2131 15.2079 19.335L14.7959 21.214C14.6954 21.6726 14.2894 21.9996 13.8199 22ZM7.61992 16.229L8.43992 16.829C8.62477 16.9652 8.81743 17.0904 9.01692 17.204C9.20462 17.3127 9.39788 17.4115 9.59592 17.5L10.5289 17.909L10.9859 20H13.0159L13.4729 17.908L14.4059 17.499C14.8132 17.3194 15.1998 17.0961 15.5589 16.833L16.3799 16.233L18.4209 16.883L19.4359 15.125L17.8529 13.682L17.9649 12.67C18.0141 12.2274 18.0141 11.7806 17.9649 11.338L17.8529 10.326L19.4369 8.88L18.4209 7.121L16.3799 7.771L15.5589 7.171C15.1997 6.90671 14.8132 6.68175 14.4059 6.5L13.4729 6.091L13.0159 4H10.9859L10.5269 6.092L9.59592 6.5C9.39772 6.58704 9.20444 6.68486 9.01692 6.793C8.81866 6.90633 8.62701 7.03086 8.44292 7.166L7.62192 7.766L5.58192 7.116L4.56492 8.88L6.14792 10.321L6.03592 11.334C5.98672 11.7766 5.98672 12.2234 6.03592 12.666L6.14792 13.678L4.56492 15.121L5.57992 16.879L7.61992 16.229ZM11.9959 16C9.78678 16 7.99592 14.2091 7.99592 12C7.99592 9.79086 9.78678 8 11.9959 8C14.2051 8 15.9959 9.79086 15.9959 12C15.9932 14.208 14.2039 15.9972 11.9959 16ZM11.9959 10C10.9033 10.0011 10.0138 10.8788 9.99815 11.9713C9.98249 13.0638 10.8465 13.9667 11.9386 13.9991C13.0307 14.0315 13.9468 13.1815 13.9959 12.09V12.49V12C13.9959 10.8954 13.1005 10 11.9959 10Z"
                                            fill="currentColor"></path>
                                    </svg>

                                    <span class="mx-1">
                                        Ayarlar
                                    </span>
                                </a>

                                <a href="/signout" live="true"
                                    class="flex items-center m-1 rounded-md p-2 text-sm text-gray-600 capitalize transition-colors duration-300 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <svg class="w-5 h-5 mx-1" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19 21H10C8.89543 21 8 20.1046 8 19V15H10V19H19V5H10V9H8V5C8 3.89543 8.89543 3 10 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21ZM12 16V13H3V11H12V8L17 12L12 16Z"
                                            fill="currentColor"></path>
                                    </svg>

                                    <span class="mx-1">
                                        Çıkış yap
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <button type="button"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                            aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen ? 'true' : 'false'">
                            <span class="sr-only">Open main menu</span>
                            <!-- Menu open: "hidden", Menu closed: "block" -->
                            <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <!-- Menu open: "block", Menu closed: "hidden" -->
                            <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="md:hidden" id="mobile-menu" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false">
                <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                    <a href="/" live="true" data-content="#container" spinner="false"
                        class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Dashboard</a>
                    <a href="/surveys" live="true" data-content="#container" spinner="false"
                        class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Anketler</a>
                    <a href="/settings" live="true" data-content="#container" spinner="false"
                        class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Ayarlar</a>
                </div>
                <div class="border-t border-gray-700 pb-3 pt-4">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-none text-white">{$user->name}</div>
                            <div class="text-sm font-medium leading-none text-gray-400">{$user->email}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <a class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white"
                            href="/settings" live="true" data-content="#container">Ayarlar</a>
                        <a class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white"
                            href="/signout" live="true" data-content="#container">Çıkış yap</a>
                    </div>
                </div>
            </div>
        </nav>
        
        <main class="flex-1">
            <div id="container"
                class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8 m-3 rounded-xl border shadow-md bg-white dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                {$content|noescape}
            </div>
        </main>
        <footer class="mt-auto border-t bg-white/90 dark:bg-slate-800/90 dark:border-slate-700">
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8 flex items-center justify-between">
                <h1 class="flex items-center font-semibold">
                    <svg class="h-8 w-8 mr-2" width="64px" height="64px" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" transform="matrix(1, 0, 0, 1, 0, 0)" stroke="#fff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#999999"
                            stroke-width="0.048"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path opacity="0.6"
                                d="M20.1004 6.93998C20.1004 7.47998 19.8104 7.96995 19.3504 8.21995L17.6104 9.15995L16.1304 9.94999L13.0604 11.61C12.7304 11.79 12.3704 11.88 12.0004 11.88C11.6304 11.88 11.2704 11.79 10.9404 11.61L4.65039 8.21995C4.19039 7.96995 3.90039 7.47998 3.90039 6.93998C3.90039 6.39998 4.19039 5.90995 4.65039 5.65995L6.62039 4.59996L8.1904 3.74998L10.9404 2.27C11.6004 1.91 12.4004 1.91 13.0604 2.27L19.3504 5.65995C19.8104 5.90995 20.1004 6.39998 20.1004 6.93998Z"
                                fill="#292D32"></path>
                            <path opacity="0.6"
                                d="M9.9007 12.7899L4.05069 9.85989C3.60069 9.62989 3.0807 9.65989 2.6507 9.91989C2.2207 10.1799 1.9707 10.6399 1.9707 11.1399V16.6699C1.9707 17.6299 2.50069 18.4899 3.36069 18.9199L9.21069 21.8399C9.41069 21.9399 9.63071 21.9899 9.85071 21.9899C10.1107 21.9899 10.3707 21.9199 10.6007 21.7699C11.0307 21.5099 11.2807 21.0499 11.2807 20.5499V15.0199C11.2907 14.0799 10.7607 13.2199 9.9007 12.7899Z"
                                fill="#492D32"></path>
                            <path opacity="0.6"
                                d="M22.0309 11.1502V16.6801C22.0309 17.6301 21.501 18.4901 20.641 18.9201L14.791 21.8501C14.591 21.9501 14.3709 22.0001 14.1509 22.0001C13.8909 22.0001 13.631 21.9302 13.391 21.7802C12.971 21.5202 12.7109 21.0601 12.7109 20.5601V15.0401C12.7109 14.0801 13.241 13.2201 14.101 12.7901L16.2509 11.7201L17.7509 10.9701L19.951 9.87013C20.401 9.64013 20.921 9.66012 21.351 9.93012C21.771 10.1901 22.0309 10.6502 22.0309 11.1502Z"
                                fill="#292D32"></path>
                        </g>
                    </svg>
                    <span class="text-gray-500">Surmey - <small class="text-gray-400 font-thin">A simple survey & reporting system</small></span>
                </h1>
                <span class="text-sm text-gray-500">Copyright © {date("Y")} <a class="hover:text-gray-700 dark:hover:text-gray-200" href="https://github.com/myildirimofficial">All rights reserved.</a></span>
            </div>
        </footer>
    </div>
</body>

</html>