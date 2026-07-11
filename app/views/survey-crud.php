<script id="survey-builder-i18n" type="application/json">{$surveyBuilderI18nJson|noescape}</script>
<div id="survey-builder-config" class="hidden"
    data-i18n="{$surveyBuilderI18nJson}"
    data-is-create="{$isSurveyCreateJs}"></div>
<div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-6 lg:px-8">
    <form role="form" action="/surveys/{$url}" before="window.generateSurvey()" method="post"
        data-block-reset="true" data-content=".survey-create-message">
        <input type="hidden" name="data" />
        {csrf()|noescape}
        <div class="survey-create-message fixed bottom-0 right-2 z-10 my-5"></div>
        <div class="border-b border-gray-900/10 pb-12">
            <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-slate-50">
                {$surveyFormTitle}
            </h2>
            <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">
                {lang("create.survey.desc")}
            </p>

            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="sc-title"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-slate-50">
                        {lang("title")}
                    </label>
                    <div class="mt-2">
                        <input type="text" name="title" id="sc-title" autocomplete="given-name"
                            class="block px-2 w-full rounded-md outline-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 dark:text-gray-50 dark:bg-slate-700 dark:ring-gray-600">
                    </div>
                </div>

                <div class="col-span-4">
                    <label class="relative inline-flex items-center mb-4 cursor-pointer">
                        <input type="checkbox" value="" name="verifyPhone" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300 select-none">
                            {lang("create.survey.phone")}
                        </span>
                    </label>
                </div>

                <div class="hidden col-span-4">
                    <label class=" relative inline-flex items-center mb-4 cursor-pointer">
                        <input type="checkbox" value="" name="anonymous" class="sr-only peer" checked>
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300 select-none">
                            {lang("survey.builder.anonymous_publish")}
                        </span>
                    </label>
                </div>

                <div class="col-span-full">
                    <label for="cover-photo"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">{lang("survey.builder.cover_photo")}</label>
                    <div id="cover-photo-result" style="background-image: url('/public/img/linkedbanner1.png')"
                        class="bg-no-repeat bg-cover bg-center h-52 mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 dark:border-gray-300/25 px-6 py-10">
                        <div class="text-center backdrop-blur-sm p-4 rounded-md bg-white/30">
                            <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="mt-4 flex text-sm leading-6 text-gray-600 dark:text-slate-300">
                                <label for="file-upload"
                                    class="relative cursor-pointer rounded-md bg-white dark:bg-slate-600 font-semibold dark:text-blue-300 text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 hover:text-blue-500">
                                    <span>{lang("survey.builder.upload_file")}</span>
                                    <input id="file-upload" name="photo" type="file" class="sr-only">
                                </label>
                                <p class="pl-1">{lang("survey.builder.drag_drop")}</p>
                            </div>
                            <p class="text-xs leading-5 text-gray-600 dark:text-gray-300">{lang("survey.builder.image_formats")}</p>
                        </div>
                    </div>
                </div>

                <div class="col-span-full">
                    <label for="about"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">{lang("survey.builder.about_survey")}</label>
                    <div class="mt-2">
                        <textarea id="about" tinymce="true" name="about"
                            class="px-4 block w-full rounded-md border-0 outline-none py-1.5 text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:bg-slate-700 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">{lang("survey.builder.about_hint")}</p>
                </div>
            </div>
        </div>

        <div x-data="{ current: 1 }" class="border-t pt-6 border-gray-900/10 dark:border-gray-600 pb-12">
            <div class="sticky top-0 z-40 -mx-2 px-2 py-2 mb-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur supports-[backdrop-filter]:bg-white/75 supports-[backdrop-filter]:dark:bg-slate-900/75 border-b border-slate-200/80 dark:border-slate-700/80 rounded-b-xl">
                <h2
                    class="font-medium leading-6 text-dark dark:text-gray-100 mb-3 flex flex-wrap items-center justify-between gap-x-6 gap-y-3">
                    <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200 px-3 py-1 text-sm font-semibold ring-1 ring-blue-200/80 dark:ring-blue-700/40">
                        {lang("survey.builder.questions")}
                    </span>

                    <div class="flex flex-wrap items-center gap-2">
                    <div x-data="{ isOpenT: false }" class="relative inline-block">
                        <button type="button" @click="isOpenT = !isOpenT"
                            class="inline-flex items-center rounded-md bg-gray-700 p-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.5 4.5h15v15h-15v-15zM8.25 8.25h7.5M8.25 12h7.5M8.25 15.75h4.5" />
                            </svg>
                            {lang("survey.builder.apply_template")}
                            <svg class="w-4 h-5 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="isOpenT" @click.away="isOpenT = false" x-transition
                            class="absolute right-0 z-30 w-64 max-h-96 overflow-y-auto py-2 mt-2 origin-top-right bg-white rounded-lg border dark:border-gray-600 shadow-xl dark:bg-gray-800">
                            <button type="button" x-on:click="isOpenT=false; applySurveyTemplate('blank')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{lang("survey.builder.template_blank")}</button>
                            <button type="button" x-on:click="isOpenT=false; applySurveyTemplate('satisfaction')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{lang("survey.builder.template_satisfaction")}</button>
                            <button type="button" x-on:click="isOpenT=false; applySurveyTemplate('contact')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{lang("survey.builder.template_contact")}</button>
                            <button type="button" x-on:click="isOpenT=false; applySurveyTemplate('event_registration')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{lang("survey.builder.template_event_registration")}</button>
                            <button type="button" x-on:click="isOpenT=false; applySurveyTemplate('customer_feedback')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{lang("survey.builder.template_customer_feedback")}</button>
                            <button type="button" x-on:click="isOpenT=false; applySurveyTemplate('employee_pulse')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{lang("survey.builder.template_employee_pulse")}</button>
                            <button type="button" x-on:click="isOpenT=false; applySurveyTemplate('lead_capture')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{lang("survey.builder.template_lead_capture")}</button>
                        </div>
                    </div>

                    <div x-data="{ isOpen: false }" class="relative inline-block">
                        <button type="button" @click="isOpen = !isOpen"
                            class="flex rounded-md bg-gray-700 p-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {lang("survey.builder.create")}
                            <svg class="w-5 h-5 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="survey-crud" x-show="isOpen" @click.away="isOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                            class="absolute right-0 z-20 w-56 max-h-96 overflow-y-auto py-2 mt-2 origin-top-right bg-white rounded-lg border dark:border-gray-600 shadow-xl dark:bg-gray-800">
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="radio" data-question-text="{lang('survey.builder.new_question')}"
                                answer-text="{lang('survey.builder.new_answer')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.radio_list")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="checkbox" data-question-text="{lang('survey.builder.new_question')}"
                                answer-text="{lang('survey.builder.new_answer')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.checkbox_list")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="select" data-question-text="{lang('survey.builder.new_question')}"
                                answer-text="{lang('survey.builder.new_answer')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.select")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="textarea" data-question-text="{lang('survey.builder.new_question')}"
                                answer-text="...."
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.textarea")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="short_text" data-question-text="{lang('survey.builder.new_question')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.short_text")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="email" data-question-text="{lang('survey.builder.new_question')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.email")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="phone" data-question-text="{lang('survey.builder.new_question')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.phone")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="url" data-question-text="{lang('survey.builder.new_question')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.url")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="date" data-question-text="{lang('survey.builder.new_question')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.date")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="time" data-question-text="{lang('survey.builder.new_question')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.time")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="number" data-question-text="{lang('survey.builder.new_question')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.number")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="scale" data-question-text="{lang('survey.builder.new_question')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.scale")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="preset_star"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.star_rating")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="preset_like_dislike"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.like_dislike_scale")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="preset_single_choice_scale"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.single_choice_scale")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="section" data-question-text="{lang('survey.builder.new_question')}"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.section")}</button>
                            <button type="button" x-on:click="isOpen = !isOpen" data-type="description" data-question-text="{lang('survey.builder.new_question')}"
                                answer-text="..."
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 transition-colors duration-300 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                {lang("survey.builder.description")}</button>
                        </div>
                    </div>
                    </div>
                </h2>

                <div
                    class="inline-flex overflow-hidden bg-white border divide-x rounded-lg dark:bg-gray-900 rtl:flex-row-reverse shadow-sm dark:border-gray-700 dark:divide-gray-700">
                    <button type="button"
                        class="cursor-default inline-flex items-center px-4 py-1 text-xs font-medium transition-colors duration-200 sm:text-sm dark:text-gray-300"
                        x-on:click="current = 1"
                        x-bind:class="{ 'text-white bg-blue-600 dark:bg-blue-700': current === 1 }">
                        <svg class="w-5 h-5 mx-1" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18 22C15.8082 21.9947 14.0267 20.2306 14 18.039V16H9.99996V18.02C9.98892 20.2265 8.19321 22.0073 5.98669 22C3.78017 21.9926 1.99635 20.1999 2.00001 17.9934C2.00367 15.7868 3.79343 14 5.99996 14H7.99996V9.99999H5.99996C3.79343 9.99997 2.00367 8.21315 2.00001 6.00663C1.99635 3.8001 3.78017 2.00736 5.98669 1.99999C8.19321 1.99267 9.98892 3.77349 9.99996 5.97999V7.99999H14V5.99999C14 3.79085 15.7908 1.99999 18 1.99999C20.2091 1.99999 22 3.79085 22 5.99999C22 8.20913 20.2091 9.99999 18 9.99999H16V14H18C20.2091 14 22 15.7909 22 18C22 20.2091 20.2091 22 18 22ZM16 16V18C16 19.1046 16.8954 20 18 20C19.1045 20 20 19.1046 20 18C20 16.8954 19.1045 16 18 16H16ZM5.99996 16C4.89539 16 3.99996 16.8954 3.99996 18C3.99996 19.1046 4.89539 20 5.99996 20C6.53211 20.0057 7.04412 19.7968 7.42043 19.4205C7.79674 19.0442 8.00563 18.5321 7.99996 18V16H5.99996ZM9.99996 9.99999V14H14V9.99999H9.99996ZM18 3.99999C17.4678 3.99431 16.9558 4.2032 16.5795 4.57952C16.2032 4.95583 15.9943 5.46784 16 5.99999V7.99999H18C18.5321 8.00567 19.0441 7.79678 19.4204 7.42047C19.7967 7.04416 20.0056 6.53215 20 5.99999C20.0056 5.46784 19.7967 4.95583 19.4204 4.57952C19.0441 4.2032 18.5321 3.99431 18 3.99999ZM5.99996 3.99999C5.4678 3.99431 4.95579 4.2032 4.57948 4.57952C4.20317 4.95583 3.99428 5.46784 3.99996 5.99999C3.99428 6.53215 4.20317 7.04416 4.57948 7.42047C4.95579 7.79678 5.4678 8.00567 5.99996 7.99999H7.99996V5.99999C8.00563 5.46784 7.79674 4.95583 7.42043 4.57952C7.04412 4.2032 6.53211 3.99431 5.99996 3.99999Z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="ml-1">{lang("survey.builder.developing")}</span>
                    </button>
                    <button type="button" id="preview"
                        class="cursor-default inline-flex items-center px-4 py-1 text-xs font-medium transition-colors duration-200 sm:text-sm dark:text-gray-300"
                        x-on:click="current = 2"
                        x-bind:class="{ 'text-white bg-blue-600 dark:bg-blue-700': current === 2 }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1 sm:w-6 sm:h-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <span class="ml-1">{lang("survey.builder.preview")}</span>
                    </button>
                </div>
            </div>

            <div
                class="rounded-2xl p-2 shadow-md bg-gradient-to-b from-slate-50 to-white border border-slate-200/70 dark:border-slate-600 dark:from-slate-700 dark:to-slate-800">
                <div x-show="current === 1" x-transition class="questions">
                    {$showIfOnEditMode|noescape}
                </div>
                <div class="preview-content p-3" x-show="current === 2" x-transition></div>
            </div>
        </div>

        <div class="flex items-center sticky bottom-0 justify-end gap-x-6 p-3 backdrop-blur-xl border-t">
            <a href="/surveys" live="true" data-content="#container" spinner="false"
                class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-50 hover:underline">{lang("survey.builder.cancel")}</a>
            <button type="submit"
                class="rounded-md bg-blue-600 dark:bg-blue-800 px-8 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">{lang("survey.builder.save")}</button>
        </div>
    </form>
</div>


<script>
    $(() => {
        if (!document.getElementById("survey-preview-style-fix")) {
            const styleTag = document.createElement("style");
            styleTag.id = "survey-preview-style-fix";
            styleTag.textContent = `
                .preview-form .preview-field h1,
                .preview-form .preview-field label,
                .preview-form .preview-field .preview-option-content,
                .preview-form .survey-section h2,
                .preview-form .survey-section p {
                    font: inherit !important;
                    letter-spacing: inherit !important;
                    text-transform: inherit !important;
                    line-height: inherit !important;
                }
                .preview-form .preview-field .preview-option-content * {
                    font: inherit !important;
                    letter-spacing: inherit !important;
                    text-transform: inherit !important;
                    line-height: inherit !important;
                }
                .questions.is-dragging #question {
                    transition: none !important;
                }
                .questions.is-dragging #question:hover {
                    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08) !important;
                }
                .questions.is-dragging .js-question-drag-handle,
                .questions.is-dragging .js-answer-drag-handle {
                    cursor: grabbing !important;
                }
                .surmey-drop-settle {
                    animation: surmeyDropSettle 240ms cubic-bezier(.2,.8,.2,1);
                    will-change: transform, box-shadow;
                }
                @keyframes surmeyDropSettle {
                    0% {
                        transform: translateY(8px) scale(0.985);
                        box-shadow: 0 0 0 2px rgba(59,130,246,.18), 0 12px 26px rgba(30,64,175,.26);
                    }
                    100% {
                        transform: translateY(0) scale(1);
                    }
                }
            `;
            document.head.appendChild(styleTag);
        }

        $("#about").tinymce({
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
            toolbar: 'undo redo | blocks | bold italic link | forecolor backcolor | insertfile  image media | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat | help | code',
            setup: function(editor) {
                editor.on('change', function() {
                    tinymce.triggerSave();
                })
            }
        });
    })
</script>