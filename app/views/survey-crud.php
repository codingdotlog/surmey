<div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-6 lg:px-8">
    <div class="mx-auto max-w-2xl">

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

                    <div class="col-span-full">
                        <label for="cover-photo"
                            class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Cover
                            photo</label>
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
                                        <span>Upload a file</span>
                                        <input id="file-upload" name="photo" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-600 dark:text-gray-300">PNG, JPG, GIF up to 10MB
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="about"
                            class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">About</label>
                        <div class="mt-2">
                            <textarea id="about" tinymce="true" name="about"
                                class="px-4 block w-full rounded-md border-0 outline-none py-1.5 text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:bg-slate-700 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"></textarea>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">Write a few sentences about
                            the survey.</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-5 border-gray-900/10 dark:border-gray-600 pb-12">
                <h2
                    class="font-medium leading-6 text-dark dark:text-gray-100 mb-6 flex items-center justify-between gap-x-6">
                    <span>Questions</span>

                    <div x-data="{ isOpen: false }" class="relative inline-block">
                        <a href="javascript:void(0)" @click="isOpen = !isOpen"
                            class="flex rounded-md bg-gray-700 p-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Create
                            <svg class="w-5 h-5 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <!-- Dropdown menu -->
                        <div id="survey-crud" x-show="isOpen" @click.away="isOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                            class="absolute right-0 z-20 w-48 py-2 mt-2 origin-top-right bg-white rounded-lg border dark:border-gray-600 shadow-xl dark:bg-gray-800">
                            <a x-on:click="isOpen = !isOpen" data-type="radio" data-question-text="New Question"
                                answer-text="New Answer" href="javascript:void(0)"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 capitalize transition-colors duration-300 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                Radio List </a>
                            <a x-on:click="isOpen = !isOpen" data-type="checkbox" data-question-text="New Question"
                                answer-text="New Answer" href="javascript:void(0)"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 capitalize transition-colors duration-300 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                Check List </a>
                            <a x-on:click="isOpen = !isOpen" data-type="textarea" data-question-text="New Question"
                                answer-text="...." href="javascript:void(0)"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 capitalize transition-colors duration-300 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                Text Area </a>
                            <a x-on:click="isOpen = !isOpen" data-type="description" data-question-text="Description Title"
                                answer-text="Description..." href="javascript:void(0)"
                                class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 capitalize transition-colors duration-300 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                Description </a>
                            <!--<a x-on:click="isOpen = !isOpen" data-type="group" data-question-text="Group Title" answer-text="Title..." href="javascript:void(0)" class="m-1 rounded-lg block px-4 py-2 text-sm text-gray-600 capitalize transition-colors duration-300 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white"> Group </a>-->
                        </div>
                    </div>
                </h2>

                <div x-data="{ current: 1 }">
                    <div
                        class="inline-flex overflow-hidden mb-3 bg-white border divide-x rounded-lg dark:bg-gray-900 rtl:flex-row-reverse shadow-sm dark:border-gray-700 dark:divide-gray-700">
                        <a href="javascript:void(0)"
                            class="cursor-default inline-flex items-center px-4 py-1 text-xs font-medium transition-colors duration-200 sm:text-sm dark:text-gray-300"
                            x-on:click="current = 1"
                            x-bind:class="{ 'text-white bg-blue-600 dark:bg-blue-700': current === 1 }">
                            <svg class="w-5 h-5 mx-1" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18 22C15.8082 21.9947 14.0267 20.2306 14 18.039V16H9.99996V18.02C9.98892 20.2265 8.19321 22.0073 5.98669 22C3.78017 21.9926 1.99635 20.1999 2.00001 17.9934C2.00367 15.7868 3.79343 14 5.99996 14H7.99996V9.99999H5.99996C3.79343 9.99997 2.00367 8.21315 2.00001 6.00663C1.99635 3.8001 3.78017 2.00736 5.98669 1.99999C8.19321 1.99267 9.98892 3.77349 9.99996 5.97999V7.99999H14V5.99999C14 3.79085 15.7908 1.99999 18 1.99999C20.2091 1.99999 22 3.79085 22 5.99999C22 8.20913 20.2091 9.99999 18 9.99999H16V14H18C20.2091 14 22 15.7909 22 18C22 20.2091 20.2091 22 18 22ZM16 16V18C16 19.1046 16.8954 20 18 20C19.1045 20 20 19.1046 20 18C20 16.8954 19.1045 16 18 16H16ZM5.99996 16C4.89539 16 3.99996 16.8954 3.99996 18C3.99996 19.1046 4.89539 20 5.99996 20C6.53211 20.0057 7.04412 19.7968 7.42043 19.4205C7.79674 19.0442 8.00563 18.5321 7.99996 18V16H5.99996ZM9.99996 9.99999V14H14V9.99999H9.99996ZM18 3.99999C17.4678 3.99431 16.9558 4.2032 16.5795 4.57952C16.2032 4.95583 15.9943 5.46784 16 5.99999V7.99999H18C18.5321 8.00567 19.0441 7.79678 19.4204 7.42047C19.7967 7.04416 20.0056 6.53215 20 5.99999C20.0056 5.46784 19.7967 4.95583 19.4204 4.57952C19.0441 4.2032 18.5321 3.99431 18 3.99999ZM5.99996 3.99999C5.4678 3.99431 4.95579 4.2032 4.57948 4.57952C4.20317 4.95583 3.99428 5.46784 3.99996 5.99999C3.99428 6.53215 4.20317 7.04416 4.57948 7.42047C4.95579 7.79678 5.4678 8.00567 5.99996 7.99999H7.99996V5.99999C8.00563 5.46784 7.79674 4.95583 7.42043 4.57952C7.04412 4.2032 6.53211 3.99431 5.99996 3.99999Z"
                                    fill="currentColor"></path>
                            </svg>
                            <span class="ml-1">Developing</span>
                        </a>
                        <a id="preview" href="javascript:void(0)"
                            class="cursor-default inline-flex items-center px-4 py-1 text-xs font-medium transition-colors duration-200 sm:text-sm dark:text-gray-300"
                            x-on:click="current = 2"
                            x-bind:class="{ 'text-white bg-blue-600 dark:bg-blue-700': current === 2 }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1 sm:w-6 sm:h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <span class="ml-1">Preview</span>
                        </a>
                    </div>

                    <div
                        class="rounded-lg p-1 shadow-md bg-slate-50 border-slate-100 dark:border-slate-600 dark:bg-slate-700">
                        <div x-show="current === 1" x-transition class="questions">
                            {$showIfOnEditMode|noescape}
                        </div>
                        <div class="preview-content p-2" x-show="current === 2" x-transition></div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12 hidden">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Notifications</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">We'll always let you know about important changes, but
                    you pick what else you want to hear about.</p>

                <div class="mt-10 space-y-10">
                    <fieldset>
                        <legend class="text-sm font-semibold leading-6 text-gray-900">By Email</legend>
                        <div class="mt-6 space-y-6">
                            <div class="relative flex gap-x-3">
                                <div class="flex h-6 items-center">
                                    <input id="comments" name="comments" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                </div>
                                <div class="text-sm leading-6">
                                    <label for="comments" class="font-medium text-gray-900">Comments</label>
                                    <p class="text-gray-500">Get notified when someones posts a comment on a posting.
                                    </p>
                                </div>
                            </div>
                            <div class="relative flex gap-x-3">
                                <div class="flex h-6 items-center">
                                    <input id="candidates" name="candidates" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                </div>
                                <div class="text-sm leading-6">
                                    <label for="candidates" class="font-medium text-gray-900">Candidates</label>
                                    <p class="text-gray-500">Get notified when a candidate applies for a job.</p>
                                </div>
                            </div>
                            <div class="relative flex gap-x-3">
                                <div class="flex h-6 items-center">
                                    <input id="offers" name="offers" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                </div>
                                <div class="text-sm leading-6">
                                    <label for="offers" class="font-medium text-gray-900">Offers</label>
                                    <p class="text-gray-500">Get notified when a candidate accepts or rejects an offer.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend class="text-sm font-semibold leading-6 text-gray-900">Push Notifications</legend>
                        <p class="mt-1 text-sm leading-6 text-gray-600">These are delivered via SMS to your mobile
                            phone.</p>
                        <div class="mt-6 space-y-6">
                            <div class="flex items-center gap-x-3">
                                <input id="push-everything" name="push-notifications" type="radio"
                                    class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                                <label for="push-everything"
                                    class="block text-sm font-medium leading-6 text-gray-900">Everything</label>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <input id="push-email" name="push-notifications" type="radio"
                                    class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                                <label for="push-email" class="block text-sm font-medium leading-6 text-gray-900">Same
                                    as email</label>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <input id="push-nothing" name="push-notifications" type="radio"
                                    class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                                <label for="push-nothing" class="block text-sm font-medium leading-6 text-gray-900">No
                                    push notifications</label>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="flex items-center sticky bottom-0 justify-end gap-x-6 p-3 backdrop-blur-xl border-t">
                <a href="/surveys" live="true" data-content="#container" spinner="false"
                    class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-50 hover:underline">Cancel</a>
                <button type="submit"
                    class="rounded-md bg-blue-600 dark:bg-blue-800 px-8 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Save</button>
            </div>
        </form>

    </div>
</div>


<script>
    $(() => {
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
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                })
            }
        });
    })
</script>