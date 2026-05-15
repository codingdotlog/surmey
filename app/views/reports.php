<div x-data="{ 
    isNotificationModalOpened: false, 
    isAnswerModalOpened: false,
    types: [],
    recipientType: 'all',
    legalPhones: '',
    charCount: 0,
    dropIsOpen: false, 
    current: 1,
    activeTab: 'email'
}" 
x-on:keydown.escape="isNotificationModalOpened = false"
x-effect="activeTab = types.includes('email') ? 'email' : (types.includes('sms') ? 'sms' : activeTab)"
x-effect="if (recipientType === 'legal') { types = types.filter(t => t !== 'email'); if (!types.includes('sms')) types.push('sms'); activeTab = 'sms'; }">
    <h1 class="text-center">{$surveyTitle}</h1>
    <div class="flex justify-between content-between w-full mb-3">
        <div class="inline-flex overflow-hidden relative bg-white border divide-x rounded-lg dark:bg-gray-900 rtl:flex-row-reverse shadow-sm dark:border-gray-700 dark:divide-gray-700">
            <a id="preview" href="javascript:void(0)" class="cursor-default inline-flex items-center px-4 py-1 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:text-gray-300" x-on:click="current = 1" x-bind:class="{ 'text-white bg-blue-600 dark:bg-blue-800': current === 1 }">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <span class="ml-1">Tablo</span>
            </a>
            <a href="javascript:void(0)" class="cursor-default inline-flex items-center px-4 py-1 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:text-gray-300" x-on:click="current = 2" x-bind:class="{ 'text-white bg-blue-600 dark:bg-blue-800': current === 2 }">
                <svg class="w-5 h-5 mx-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M21 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V3M6 15L10 11L14 15L20 9M20 9V13M20 9H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
                <span class="ml-1">Çizelge</span>
            </a>

            <a href="javascript:void(0)" class="cursor-default inline-flex items-center px-4 py-1 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:text-gray-300" x-on:click="current = 3" x-bind:class="{ 'text-white bg-blue-600 dark:bg-blue-800': current === 3 }">
                <svg class="w-5 h-5 mx-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M2.99902 3V16.2C2.99902 17.8802 2.99902 18.7202 3.326 19.362C3.61362 19.9265 4.07257 20.3854 4.63705 20.673C5.27879 21 6.11887 21 7.79902 21H20.999M19.9998 15H15.9998M12.9998 7.00002H6.99978M17.9998 11H8.99978" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
                <span class="ml-1">Hizalanmış</span>
            </a>

            <a n:if="!$anonymous" href="javascript:void(0)" class="cursor-default inline-flex items-center px-4 py-1 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:text-gray-300" x-on:click="current = 4" x-bind:class="{ 'text-white bg-blue-600 dark:bg-blue-800': current === 4 }">
                <svg class="w-5 h-5 mx-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M19 14C21.2091 14 23 16 23 17.5C23 18.3284 22.3284 19 21.5 19H21M17 11C18.6569 11 20 9.65685 20 8C20 6.34315 18.6569 5 17 5M5 14C2.79086 14 1 16 1 17.5C1 18.3284 1.67157 19 2.5 19H3M7 11C5.34315 11 4 9.65685 4 8C4 6.34315 5.34315 5 7 5M16.5 19H7.5C6.67157 19 6 18.3284 6 17.5C6 15 9 14 12 14C15 14 18 15 18 17.5C18 18.3284 17.3284 19 16.5 19ZM15 8C15 9.65685 13.6569 11 12 11C10.3431 11 9 9.65685 9 8C9 6.34315 10.3431 5 12 5C13.6569 5 15 6.34315 15 8Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
                <span class="relative ml-1">
                    Katılanlar <b>({count($participators)})</b>
                </span>
            </a>
        </div>
        <div class="flex justify-items-end">
            <!-- Notification Modal -->
            <div 
            x-effect="activeTab = types.includes('email') ? 'email' : (types.includes('sms') ? 'sms' : '')"
            @toggle-notification-type.window="
                if ($event.detail.type === 'email') {
                    activeTab = 'email';
                }
            ">
                            <button @click="isNotificationModalOpened = true" class="inline-flex items-center px-4 py-2 me-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                Bildirim Gönder
                            </button>

                            <div x-show="isNotificationModalOpened" @click="isNotificationModalOpened = false" class="fixed z-30 bg-black/50 backdrop-blur-sm top-0 left-0 w-full h-full outline-none"></div>

                            <div x-show="isNotificationModalOpened" x-transition class="z-40 fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto">
                                <div class="sm:h-[calc(100%-3rem)] max-w-2xl my-6 mx-auto relative w-auto pointer-events-none">
                                    <div class="max-h-full overflow-hidden border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-slate-900 dark:text-gray-50 bg-clip-padding rounded-lg outline-none text-current">
                                        <div class="flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600 rounded-t-md bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                            <h5 class="text-xl font-medium leading-normal flex items-center">
                                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                </svg>
                                                Bildirim Gönder
                                            </h5>
                                            <button @click="isNotificationModalOpened = false" class="text-white hover:text-gray-200 transition-colors duration-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="notification-message"></div>

                                        <form id="emailForm" data-block-reset="true" class="overflow-auto" role="form" action="/reports/sendNotification/{$surveyId}" method="post" data-content=".notification-message" x-on:submit="$event.target.types.value = types.join(',');">
                                            <div class="relative p-6">

                                                <div class="mb-6">
                                                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Bildirim Tipi</label>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div class="relative flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 cursor-pointer transition-all duration-300" 
                                                            :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': types.includes('sms') }"
                                                            @click="types = types.includes('sms') ? types.filter(t => t !== 'sms') : [...types, 'sms']">
                                                            <input type="checkbox" value="sms" class="hidden" x-model="types">
                                                            <input type="hidden" name="types" :value="types.join(',')">
                                                            <div class="flex items-center">
                                                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" :class="{ 'text-blue-600': types.includes('sms'), 'text-gray-400': !types.includes('sms') }" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                                </svg>
                                                                <span class="text-sm font-medium" :class="{ 'text-blue-600': types.includes('sms') }">SMS</span>
                                                            </div>
                                                            <div class="absolute top-2 right-2" x-show="types.includes('sms')">
                                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="relative flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 cursor-pointer transition-all duration-300" 
                                                            x-show="recipientType !== 'legal'"
                                                            :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': types.includes('email') }"
                                                            @click="types = types.includes('email') ? types.filter(t => t !== 'email') : [...types, 'email']">
                                                            <input type="checkbox" value="email" class="hidden" x-model="types">
                                                            <div class="flex items-center">
                                                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" :class="{ 'text-blue-600': types.includes('email'), 'text-gray-400': !types.includes('email') }" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                                </svg>
                                                                <span class="text-sm font-medium" :class="{ 'text-blue-600': types.includes('email') }">Email</span>
                                                            </div>
                                                            <div class="absolute top-2 right-2" x-show="types.includes('email')">
                                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Alıcı Tipi -->
                                                <div class="mb-6" x-show="types.length > 0" x-transition>
                                                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Alıcı Tipi</label>
                                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                                        <label class="relative flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 cursor-pointer transition-all duration-300" :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': recipientType === 'all' }">
                                                            <input type="radio" name="recipientType" value="all" class="sr-only" x-model="recipientType">
                                                            <div class="flex items-center">
                                                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                                </svg>
                                                                <span class="text-sm font-medium">Tümü</span>
                                                            </div>
                                                        </label>
                                                        <label class="relative flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 cursor-pointer transition-all duration-300" :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': recipientType === 'department' }">
                                                            <input type="radio" name="recipientType" value="department" class="sr-only" x-model="recipientType">
                                                            <div class="flex items-center">
                                                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                                </svg>
                                                                <span class="text-sm font-medium">Departman</span>
                                                            </div>
                                                        </label>
                                                        <label class="relative flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 cursor-pointer transition-all duration-300" :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': recipientType === 'individual' }">
                                                            <input type="radio" name="recipientType" value="individual" class="sr-only" x-model="recipientType">
                                                            <div class="flex items-center">
                                                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                </svg>
                                                                <span class="text-sm font-medium">Kişi</span>
                                                            </div>
                                                        </label>
                                                        <label class="relative flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 cursor-pointer transition-all duration-300" :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': recipientType === 'legal' }">
                                                            <input type="radio" name="recipientType" value="legal" class="sr-only" x-model="recipientType">
                                                            <div class="flex items-center">
                                                                <svg class="w-6 h-6 mr-2 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                                </svg>
                                                                <span class="text-sm font-medium">Tüzel Kişi</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="mb-6 department-select" x-show="recipientType === 'department'" x-transition>
                                                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Departmanlar</label>
                                                    <div class="mb-4">
                                                        <select multiple="" name="recipients[]" data-hs-select='{
                                                            "hasSearch": true,
                                                            "searchPlaceholder": "Ara...",
                                                            "searchClasses": "block w-full sm:text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-1.5 sm:py-2 px-3",
                                                            "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                                                            "placeholder": "Çoklu seçim yapabilirsiniz...",
                                                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-hidden dark:focus:ring-1 dark:focus:ring-neutral-600",
                                                            "toggleSeparators": {
                                                            "betweenItemsAndCounter": "&"
                                                            },
                                                            "toggleCountText": "+",
                                                            "toggleCountTextPlacement": "prefix-no-space",
                                                            "toggleCountTextMinItems": 3,
                                                            "toggleCountTextMode": "nItemsAndCount",
                                                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                                                            "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                                                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600 dark:text-blue-500 \" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                                                            "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                                        }' class="hidden">
                                                            <option n:foreach="$departmentList as $dept" value="{$dept}">{$dept}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-6 person-select" x-show="recipientType === 'individual'" x-transition>
                                                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Kişiler</label>
                                                    <select multiple="" name="recipients[]" data-hs-select='{
                                                                    "hasSearch": true,
                                                                    "searchPlaceholder": "Ara...",
                                                                    "searchClasses": "block w-full sm:text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-1.5 sm:py-2 px-3",
                                                                    "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                                                                    "placeholder": "Çoklu seçim yapabilirsiniz...",
                                                                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                                                    "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-hidden dark:focus:ring-1 dark:focus:ring-neutral-600",
                                                                    "toggleSeparators": {
                                                                    "betweenItemsAndCounter": "&"
                                                                    },
                                                                    "toggleCountText": "+",
                                                                    "toggleCountTextPlacement": "prefix-no-space",
                                                                    "toggleCountTextMinItems": 3,
                                                                    "toggleCountTextMode": "nItemsAndCount",
                                                                    "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                                                                    "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                                                                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600 dark:text-blue-500 \" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                                                                    "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                                                }' class="hidden">
                                                        <option n:foreach="$personList as $person" value="{$person->id}">{$person->fullname}</option>
                                                    </select>
                                                </div>

                                                <div class="mb-6 legal-select" x-show="recipientType === 'legal'" x-transition>
                                                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Telefon Numaraları</label>
                                                    <textarea name="legal_phones" rows="4" x-model="legalPhones"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Her satıra bir numara veya virgülle ayırarak yazın&#10;Örn: 5321234567, 5339876543"></textarea>
                                                    <p class="mt-1 text-sm text-gray-500">Tüzel kişi bildirimleri yalnızca SMS ile gönderilir.</p>
                                                </div>
                                                
                                                <div class="mb-6" x-show="types.length > 0" x-transition>
                                                    <div class="border-b border-gray-200 dark:border-gray-700">
                                                        <nav class="flex space-x-2" aria-label="Tabs" role="tablist">
                                                            <button type="button"
                                                                class="py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600"
                                                                :class="{ 'font-semibold border-blue-600 text-blue-600': types.includes('sms') && activeTab === 'sms' }"
                                                                @click="activeTab = 'sms'"
                                                                x-show="types.includes('sms')"
                                                                x-transition>
                                                                SMS İçeriği
                                                            </button>
                                                            <button type="button"
                                                                class="py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600"
                                                                :class="{ 'font-semibold border-blue-600 text-blue-600': types.includes('email') && activeTab === 'email' }"
                                                                @click="activeTab = 'email'"
                                                                x-show="types.includes('email')"
                                                                x-transition>
                                                                E-posta İçeriği
                                                            </button>
                                                        </nav>
                                                    </div>

                                                    <!-- Tab İçerikleri -->
                                                    <div class="mt-3">
                                                        <div x-show="activeTab === 'sms' && types.includes('sms')" x-transition>
                                                            <div class="relative">
                                                                <textarea name="sms_message" rows="4" maxlength="300" 
                                                                    x-on:input="charCount = $event.target.value.length"
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                    placeholder="SMS mesajınızı yazın..."
                                                                    :required="types.includes('sms')"></textarea>
                                                                <div class="absolute bottom-2 right-2">
                                                                    <span class="text-sm text-gray-500" x-text="charCount + '/300'"></span>
                                                                </div>
                                                            </div>
                                                            <p class="mt-1 text-sm text-gray-500">Maksimum 300 karakter</p>
                                                        </div>

                                                        <div x-show="activeTab === 'email' && types.includes('email')" x-transition>
                                                            <!-- E-posta Başlığı -->
                                                            <div class="mb-4">
                                                                <input type="text" name="email_subject"
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                    placeholder="E-posta başlığı..."
                                                                    :required="types.includes('email')">
                                                            </div>
                                                            <!-- E-posta İçeriği -->
                                                            <textarea tinymce="true" name="email_message" id="email_editor" rows="8" 
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                :required="types.includes('email')"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 dark:border-gray-600 rounded-b-md bg-gray-50 dark:bg-gray-800">
                                                <button @click="isNotificationModalOpened = false" type="button" class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-all duration-300 mr-4">
                                                    İptal
                                                </button>
                                                <button type="submit"
                                                    class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300"
                                                    :disabled="types.length === 0 || (types.includes('sms') && recipientType !== 'legal' && (charCount === 0 || charCount > 300)) || (recipientType === 'legal' && (!legalPhones.trim() || charCount === 0 || charCount > 300))">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                    </svg>
                                                    Gönder
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
            <div class="relative inline-flex">
                <button id="dropdownMenuIconButton" @click="dropIsOpen = !dropIsOpen" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600" type="button">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                        <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdownRadioHelper" x-show="dropIsOpen" @click.away="dropIsOpen = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="absolute right-0 top-8 py-2 mt-2 overflow-hidden origin-top z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-60 border dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="p-1 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioHelperButton">
                        <li>
                            <a href="/reports/csv/{$surveyId}" target="blank" class="flex items-center content-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path opacity="0.5" d="M4 12C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12L4 12Z" fill="currentColor"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.5303 7.53033C15.2374 7.82322 14.7626 7.82322 14.4697 7.53033L12.75 5.81066L12.75 14C12.75 14.4142 12.4142 14.75 12 14.75C11.5858 14.75 11.25 14.4142 11.25 14L11.25 5.81066L9.53033 7.53033C9.23744 7.82322 8.76256 7.82322 8.46967 7.53033C8.17678 7.23744 8.17678 6.76256 8.46967 6.46967L11.4697 3.46967C11.7626 3.17678 12.2374 3.17678 12.5303 3.46967L15.5303 6.46967C15.8232 6.76256 15.8232 7.23744 15.5303 7.53033Z" fill="currentColor"></path>
                                    </g>
                                </svg>
                                <div class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    <div>EXCEL</div>
                                    <p id="helper-radio-text-4" class="text-xs font-normal text-gray-500 dark:text-gray-300">
                                        Sonuçları excel formatında dışarı aktar
                                    </p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-url="/reports/reset/{$surveyId}" class="flex items-center content-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M3 6.52381C3 6.12932 3.32671 5.80952 3.72973 5.80952H8.51787C8.52437 4.9683 8.61554 3.81504 9.45037 3.01668C10.1074 2.38839 11.0081 2 12 2C12.9919 2 13.8926 2.38839 14.5496 3.01668C15.3844 3.81504 15.4756 4.9683 15.4821 5.80952H20.2703C20.6733 5.80952 21 6.12932 21 6.52381C21 6.9183 20.6733 7.2381 20.2703 7.2381H3.72973C3.32671 7.2381 3 6.9183 3 6.52381Z" fill="currentColor"></path>
                                        <path opacity="0.5" d="M11.5956 22.0001H12.4044C15.1871 22.0001 16.5785 22.0001 17.4831 21.1142C18.3878 20.2283 18.4803 18.7751 18.6654 15.8686L18.9321 11.6807C19.0326 10.1037 19.0828 9.31524 18.6289 8.81558C18.1751 8.31592 17.4087 8.31592 15.876 8.31592H8.12405C6.59127 8.31592 5.82488 8.31592 5.37105 8.81558C4.91722 9.31524 4.96744 10.1037 5.06788 11.6807L5.33459 15.8686C5.5197 18.7751 5.61225 20.2283 6.51689 21.1142C7.42153 22.0001 8.81289 22.0001 11.5956 22.0001Z" fill="currentColor"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.42543 11.4815C9.83759 11.4381 10.2051 11.7547 10.2463 12.1885L10.7463 17.4517C10.7875 17.8855 10.4868 18.2724 10.0747 18.3158C9.66253 18.3592 9.29499 18.0426 9.25378 17.6088L8.75378 12.3456C8.71256 11.9118 9.01327 11.5249 9.42543 11.4815Z" fill="currentColor"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5747 11.4815C14.9868 11.5249 15.2875 11.9118 15.2463 12.3456L14.7463 17.6088C14.7051 18.0426 14.3376 18.3592 13.9254 18.3158C13.5133 18.2724 13.2126 17.8855 13.2538 17.4517L13.7538 12.1885C13.795 11.7547 14.1625 11.4381 14.5747 11.4815Z" fill="currentColor"></path>
                                    </g>
                                </svg>
                                <div class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    <div>Katılımcıları Sıfırla</div>
                                    <p id="helper-radio-text-4" class="text-xs font-normal text-gray-500 dark:text-gray-300">
                                        Geçerli ankete katılan tüm katılımcıları siler ve temizler.
                                    </p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <div class="relative">

        <div x-show="current === 1">
            <div class="dark:bg-gray-900 bg-gray-100 rounded-lg m-1 my-2 p-3 shadow-sm" n:foreach="$data as $key => $value">
                <h1 class="text-xl text-center">{str_ireplace('\"', '"',$key)|noescape}</h1>
                <div class="grid grid-cols-1 xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-2 content-between mt-2">
                    <div class="dark:bg-gray-800 bg-white rounded-xl m-1 my-3 overflow-hidden" n:foreach="$value['answers'] as $aK => $aV">
                        <span class="block text-center bg-blue-600 text-white p-1 overflow-hidden">{str_ireplace('\"', '"', $aK)|noescape}</span>
                        <span class="block text-4xl text-center text-gray-500 p-1 dark:text-gray-300">
                            {if $value["type"] != "radio" && $value["type"] != "checkbox" && $aK != "Boş"}
                            <button @click="isAnswerModalOpened = !isAnswerModalOpened" title="Anonim Cevapları Göster" class="text-blue-500 underline dark:text-blue-400 font-bold hover:bg-blue-500 rounded-xl transition-colors duration-200 hover:text-white" data-json='{$value["list-json"]|noescape}'>{$aV}</button>
                            {else}
                            {$aV}
                            {/if}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-between mt-2" x-show="current === 2">
            <button data-chart-type="pie" class="p-1 bg-gray-900 text-white rounded-lg m-1 my-2 shadow-sm">
                <svg class="w-8 h-8" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="currentColor">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <style type="text/css">
                            .st0 {
                                fill: none;
                                stroke: currentColor;
                                stroke-width: 2;
                                stroke-linecap: round;
                                stroke-linejoin: round;
                                stroke-miterlimit: 10;
                            }
                        </style>
                        <g>
                            <path d="M27.7,8.3l-10.2,7.8l9.1,9.1c2.1-2.5,3.4-5.7,3.4-9.2C30,13.1,29.1,10.5,27.7,8.3z"></path>
                            <path d="M17,14l9.4-7.3c-2.4-2.6-5.7-4.4-9.4-4.7V14z"></path>
                            <path d="M15.3,16.7C15.3,16.7,15.3,16.7,15.3,16.7C15.2,16.6,15.2,16.6,15.3,16.7c-0.1-0.1-0.1-0.2-0.1-0.2c0-0.1-0.1-0.1-0.1-0.2 s0-0.1,0-0.2c0,0,0-0.1,0-0.1V2.1C7.7,2.6,2,8.6,2,16c0,7.7,6.3,14,14,14c3.5,0,6.7-1.3,9.2-3.4L15.3,16.7z"></path>
                        </g>
                    </g>
                </svg>
            </button>
            <button data-chart-type="bar" class="bg-gray-100 text-blue-600 p-1 rounded-lg m-1 my-2 shadow-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 -8 72 72" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <title>bar-chart</title>
                        <g id="Layer_5" data-name="Layer 5">
                            <path d="M61,49.12c0,1-.27,1.88-1.57,1.88H13.35A2.36,2.36,0,0,1,11,48.65V5.22c0-1.3.85-1.57,1.88-1.57s1.88.27,1.88,1.57V44.89a2.36,2.36,0,0,0,2.35,2.35H59.43C60.73,47.24,61,48.08,61,49.12Z"></path>
                        </g>
                        <path d="M22.13,44h3.12a1.55,1.55,0,0,0,1.55-1.56V26.8a1.55,1.55,0,0,0-1.55-1.56H22.13a1.56,1.56,0,0,0-1.56,1.56V42.39A1.56,1.56,0,0,0,22.13,44Z"></path>
                        <path d="M31.37,43.63h3.26A1.63,1.63,0,0,0,36.26,42V12.65A1.63,1.63,0,0,0,34.63,11H31.37a1.63,1.63,0,0,0-1.63,1.63V42A1.63,1.63,0,0,0,31.37,43.63Z"></path>
                        <path d="M41.15,43.63h3.27A1.63,1.63,0,0,0,46.05,42V32.21a1.63,1.63,0,0,0-1.63-1.63H41.15a1.63,1.63,0,0,0-1.63,1.63V42A1.63,1.63,0,0,0,41.15,43.63Z"></path>
                        <path d="M50.94,43.63H54.2A1.63,1.63,0,0,0,55.83,42V19.17a1.63,1.63,0,0,0-1.63-1.63H50.94a1.63,1.63,0,0,0-1.63,1.63V42A1.63,1.63,0,0,0,50.94,43.63Z"></path>
                    </g>
                </svg>
            </button>
            <button data-chart-type="line" class="bg-gray-100 text-blue-600 p-1 rounded-lg m-1 my-2 shadow-sm">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M21 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V3M7 7L12 13L16 9L21 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
            </button>

            <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2">
                <div class="dark:bg-gray-900 bg-gray-100 rounded-lg m-1 my-2 p-3 shadow-sm" n:foreach="$data as $key => $value">
                    <h1 class="text-current text-center h-12" title="{str_ireplace('\"', '"',$key)|noescape}">{str_ireplace('\"', '"',$key)|noescape}</h1>
                    <canvas class="dark:bg-gray-800 bg-white rounded-xl m-1 my-3 overflow-hidden" chart-data="{json_encode($value['answers'])|noescape}" id="chart-{$random=mt_rand(0, PHP_INT_MAX)}"></canvas>
                </div>
            </div>
        </div>

        <div class="content-between mt-2" x-show="current === 3">
            <div class="dark:bg-gray-900 bg-gray-100 rounded-lg m-1 my-2 p-3 shadow-sm" n:foreach="$data as $key => $value">
                <h1 class="text-xl text-center h-12 truncate" title="{str_ireplace('\"', '"',$key)|noescape}">{str_ireplace('\"', '"',$key)|noescape}</h1>
                <div class="grid grid-cols-1 lg:grid-cols-4 md:grid-cols-2 content-between mt-2">
                    <div class="dark:bg-gray-800 bg-white rounded-xl m-1 my-3 overflow-hidden" n:foreach="$value['answers'] as $aK => $aV">
                        <!-- Progress -->
                        <div class="p-4">
                            <div class="mb-2 flex justify-between items-center">
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">{str_ireplace('\"', '"', $aK)|noescape}</h3>
                                <span class="text-sm text-gray-800 dark:text-white">{round($aV * 100 / ($value["total"] == 0 ? 1 : $value["total"]), 1)}%</span>
                            </div>
                            <div class="flex w-full h-4 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700">
                                <div class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-blue-500" style="width: {round($aV * 100 / ($value['total']==0 ? 1 : $value['total']), 1)|noescape}%"></div>
                            </div>
                        </div>
                        <!-- End Progress -->
                    </div>
                </div>
            </div>
        </div>

        <div n:if="!$anonymous" x-show="current === 4" x-transition class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <button class="flex items-center gap-x-3 focus:outline-none">
                                <span>Fullname</span>

                                <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                </svg>
                            </button>
                        </th>

                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <button class="flex items-center gap-x-3 focus:outline-none">
                                <span>Phone</span>

                                <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                </svg>
                            </button>
                        </th>

                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <button class="flex items-center gap-x-3 focus:outline-none">
                                <span>Status</span>

                                <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                </svg>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                    <tr n:foreach="$participators as $participator">
                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                            <div>
                                <h2 class="font-medium text-gray-800 dark:text-white">{$participator->fullname}</h2>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                            <div>
                                <h2 class="font-medium text-gray-800 dark:text-white">{(empty($participator->phone1) ? $participator->phone2 : $participator->phone1)}</h2>
                            </div>
                        </td>
                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="inline px-3 py-1 text-sm font-normal rounded-full gap-x-2 {$participator->status == 1 ? ' text-emerald-500 bg-emerald-100/60' : 'text-gray-500 bg-gray-100 dark:text-gray-400'} dark:bg-gray-800">
                                {$participator->status == 1 ? 'ACTIVE' : 'DEACTIVE'}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div x-show="isAnswerModalOpened" @click="isAnswerModalOpened = false" class="fixed z-30 bg-black/50 backdrop-blur-sm top-0 left-0 w-full h-full outline-none"></div>
        <div class="z-40 fixed top-0 left-0 w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="answersModalScrollable" tabindex="-1" aria-labelledby="answersModalScrollableLabel" aria-hidden="true" x-show="isAnswerModalOpened" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95" x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100" x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100" x-transition:leave-end="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95">
            <div class="sm:h-[calc(100%-3rem)] max-w-2xl my-6 mx-auto relative w-auto pointer-events-none">
                <div class="max-h-full overflow-hidden border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-slate-900 dark:text-gray-50 bg-clip-padding rounded-lg outline-none text-current">
                    <div class="flex flex-shrink-0 items-center justify-between p-2 border-b border-gray-200 dark:border-gray-600 rounded-t-md">
                        <h5 class="text-xl flex font-medium leading-normal text-gray-800 dark:text-gray-200" id="answersModalScrollableLabel">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <span class="ml-2" id="answers-modal-title">Loading....</span>
                        </h5>

                        <button @click="isAnswerModalOpened = false" class="box-content w-full mt-2 tracking-wide text-gray-700 capitalize transition-colors duration-300 transform rounded-md sm:mt-0 sm:w-auto sm:mx-2 dark:text-gray-200  hover:text-red-600  focus:outline-none focus:ring focus:ring-opacity-40">
                            <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path opacity="0.4" d="M16.19 2H7.81C4.17 2 2 4.17 2 7.81V16.18C2 19.83 4.17 22 7.81 22H16.18C19.82 22 21.99 19.83 21.99 16.19V7.81C22 4.17 19.83 2 16.19 2Z" fill="currentColor"></path>
                                    <path d="M13.0594 12.0001L15.3594 9.70011C15.6494 9.41011 15.6494 8.93011 15.3594 8.64011C15.0694 8.35011 14.5894 8.35011 14.2994 8.64011L11.9994 10.9401L9.69937 8.64011C9.40937 8.35011 8.92937 8.35011 8.63938 8.64011C8.34938 8.93011 8.34938 9.41011 8.63938 9.70011L10.9394 12.0001L8.63938 14.3001C8.34938 14.5901 8.34938 15.0701 8.63938 15.3601C8.78938 15.5101 8.97937 15.5801 9.16937 15.5801C9.35937 15.5801 9.54937 15.5101 9.69937 15.3601L11.9994 13.0601L14.2994 15.3601C14.4494 15.5101 14.6394 15.5801 14.8294 15.5801C15.0194 15.5801 15.2094 15.5101 15.3594 15.3601C15.6494 15.0701 15.6494 14.5901 15.3594 14.3001L13.0594 12.0001Z" fill="currentColor"></path>
                                </g>
                            </svg>
                        </button>
                    </div>
                    <div class="flex-auto overflow-y-auto relative p-4">
                        <div id="answersModalContent" class="mt-2"></div>
                    </div>
                    <div class="p-4 mt-5 sm:flex sm:items-center sm:justify-between">
                        <div class="sm:flex sm:items-center ">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        // Add handler for reset link
        $("[data-url^='/reports/reset/']").on("click", function(e) {
            e.preventDefault();
            if (confirm("Katılımcıları sıfırlamak istediğinize emin misiniz? Bu işlem geri alınamaz."))
                return true;
            else
                return false;
        });

        const charts = []
        $("[data-json]").on("click", function() {

            const jsonData = $(this).data("json");

            const questionTitle = $(this).parent().parent().parent().prev().text();
            $("#answers-modal-title").text(questionTitle)

            $("#answersModalContent").html("")

            jsonData.forEach(element => {
                $("#answersModalContent").prepend('<div class="dark:bg-gray-700 bg-gray-50 my-2 text-sm text-gray-900 dark:text-gray-200 p-3 rounded-md">' + element.value + '</div>');
            });
        })

        $("[chart-data]").each((index, element) => {

            $this = $(element)
            const chartData = $this.attr("chart-data");
            const data = JSON.parse(chartData)

            const ctx = document.getElementById($this.attr("id"));

            const keys = Object.keys(data).map((v) => v.replace(/\\"/g, '"').replace(/<[^>]*>?/gm, ''))
            console.log(keys)
            var chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: keys,
                    datasets: [{
                        label: '',
                        data: Object.values(data),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {

                    },
                }
            })

            charts.push(chart)
        })

        $("[data-chart-type]").on("click", (e) => {

            const chartType = $(e.currentTarget).data("chart-type")

            charts.forEach(element => {
                element.config.type = chartType
                element.update()
            });

            $("[data-chart-type]").each((index, element) => {
                $(element).removeClass("bg-gray-900 text-white").addClass("bg-gray-100 text-blue-600")
            })

            $(e.currentTarget).removeClass("bg-gray-100 text-blue-600").addClass("bg-gray-900 text-white")
        })

        $("#email_editor").tinymce({
            height: 300,
            menubar: false,
            paste_data_images: true,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount', 'iframe'
            ],
            toolbar: 'blocks | bold italic link | forecolor backcolor | insertfile  image media | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat | help | code',
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                })
            }
        });
    })
</script>