$(function () {
    const I18N_FALLBACKS = {
        "survey.builder.drag": "Sürükle",
        "survey.builder.negative_value": "Beğenmedim",
        "survey.builder.positive_value": "Beğendim",
        "survey.builder.neutral_value": "Kararsızım",
        "survey.builder.required": "Zorunlu",
        "survey.builder.horizontal_layout": "Yatay olarak diz",
        "survey.builder.condition_none": "Seçili değil",
        "survey.builder.close": "Kapat"
    };
    const builderConfig = document.getElementById("survey-builder-config");
    const i18nScript = document.getElementById("survey-builder-i18n");
    if (i18nScript) {
        try {
            const jsonText = (i18nScript.textContent || "").trim();
            if (jsonText) window.__SURMEY_I18N = JSON.parse(jsonText);
        } catch (e) { }
    }
    if (builderConfig) {
        try {
            if (!window.__SURMEY_I18N || !Object.keys(window.__SURMEY_I18N).length) {
                const attrRaw = builderConfig.getAttribute("data-i18n");
                if (attrRaw) window.__SURMEY_I18N = JSON.parse(attrRaw);
            }
        } catch (e) { }
        window.__SURMEY_SURVEY_CREATE = builderConfig.getAttribute("data-is-create") === "true";
    }

    function linkify(text) {
        return text;
        var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        return text.replace(urlRegex, function (url) {
            return '<a class="text-blue-600 underline" href="' + url + '">' + url + '</a>';
        });
    }

    const T = function (key, fallback) {
        if (window.__SURMEY_I18N && window.__SURMEY_I18N[key]) return window.__SURMEY_I18N[key];
        if (fallback === undefined && I18N_FALLBACKS[key]) return I18N_FALLBACKS[key];
        return fallback !== undefined ? fallback : key;
    };
    const baseInputClass = "w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 px-2 py-1.5 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500";

    var uniqueStrings = [];
    const randomString = (length) => {
        const chars =
            "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        while (true) {
            var result = "";
            for (var i = length; i > 0; --i)
                result += chars[Math.floor(Math.random() * chars.length)];
            if (uniqueStrings.find((p) => p == result)) continue;
            uniqueStrings.push(result);
            break;
        }
        return result;
    };

    const clearText = (text) => {
        return text
            .trim()
            .replaceAll("\r\n", "<br>")
            .replaceAll("\r", "<br/>")
            .replaceAll("\n", "<br>")
            .replaceAll("\t", "   ")
            .replaceAll('"', '\\\"');
    };

    $("body").on("change", "#file-upload", function (event) {
        var input = event.currentTarget;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#cover-photo-result").css(
                    "background-image",
                    "url(" + e.target.result + ")"
                );
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    const initTinymce = () => {
        $("[contenteditable]:not(#create-answer)").tinymce({
            inline: true,
            menubar: false,
            paste_data_images: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'fullscreen',
                'insertdatetime', 'media', 'table', 'iframe'
            ],
            toolbar: 'blocks | bold italic underline link | forecolor backcolor | insertfile image media | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat preview',
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                })

                editor.on('init', function () {
                    $(editor.getDoc()).on('keydown', function (e) {
                        if (e.key === "Enter") {
                            e.preventDefault();
                        }
                    });
                });
            }
        })
    }

    const getQuestionComponent = (type, body, isRequired = false, conditions = [], isCheckable = false, isHorizontal = false, hideControls = false) => {
        const showBar = !hideControls && type !== "description" && type !== "section";
        return `
            <div data-type="${type}" conditions='${JSON.stringify(conditions)}' id="question" class="relative border rounded-xl bg-white dark:bg-gray-800 border-gray-900/10 dark:border-gray-300/20 px-3 pt-3 text-sm m-3 shadow-sm hover:shadow-md transition-shadow duration-200">
                <button type="button" class="remove absolute -top-2 -right-3 bg-red-500/90 transition duration-300 shadow rounded-full p-1.5 inline-flex items-center justify-center text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    <span class="sr-only">${T("survey.builder.close")}</span>

                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <button type="button" class="js-question-drag-handle absolute top-6 -right-3 bg-slate-500 dark:bg-slate-600 transition duration-200 rounded-full p-2 inline-flex items-center justify-center text-white hover:bg-slate-600 focus:outline-none cursor-grab active:cursor-grabbing" draggable="true" title="${T("survey.builder.drag", "Sürükle")}">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9 6h.01M9 12h.01M9 18h.01M15 6h.01M15 12h.01M15 18h.01" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                ${body}

                ${showBar ? `
                <div class="rounded-lg bg-yellow-50 dark:bg-gray-900/70 p-2 mb-2">
                    <div class="col-span-4">
                        <label class="relative inline-flex items-center mb-4 cursor-pointer">
                            <input type="checkbox" ${isRequired ? "checked" : ""} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300 select-none">
                                ${T("survey.builder.required")}
                            </span>
                        </label>
                        ${isCheckable ? `
                            <label class="${type == "checkbox" || type == "radio" ? "" : "hidden"} relative inline-flex items-center mb-4 cursor-pointer">
                                <input type="checkbox" ${isHorizontal ? "checked" : ""} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300 select-none">
                                    ${T("survey.builder.horizontal_layout")}
                                </span>
                            </label>
                        ` : ''}
                    </div>
                </div>` : ''}
            </div>
        `;
    }

    $(document).on("click", "#condition-dropdown ul li", (e) => {
        e.preventDefault()

        const question = $(e.currentTarget).parents("#question")
        const answerIndex = $(e.currentTarget).parents("#answer").index()
        const val = $(e.currentTarget).children("input").val()

        var conditions = JSON.parse(question.attr("conditions") ?? "[]")

        arrayIndex = conditions.findIndex(p => p.index == answerIndex)
        if (arrayIndex != -1) {
            if (val == "none")
                conditions.splice(arrayIndex, 1)
            else
                conditions[arrayIndex].value = val;
        }
        else
            conditions.push({
                index: answerIndex,
                value: val
            })


        question.attr("conditions", JSON.stringify(conditions))
        $(e.currentTarget).parents("#condition-dropdown").addClass("hidden")
    })

    $(document).on("click", "#condition", (e) => {

        const dropdown = $(e.currentTarget).closest("#answer").find("#condition-dropdown").first();
        $("#condition-dropdown:not(.hidden)").each((i, ee) => {
            if (dropdown.is($(ee)))
                return;

            $(ee).addClass("hidden")
        })

        dropdown.toggleClass("hidden")

        if (dropdown.hasClass("hidden"))
            return false

        const parentIndex = $(e.currentTarget).parents("#answer").index()

        const questions = generate()
        const ul = dropdown.find("ul")
        ul.html("")
        ul.append(`
            <li>
                <input checked type="radio" id="first-condition-element" name="condition-${parentIndex}" value="none" class="hidden peer" required />
                <label for="first-condition-element" class="inline-flex items-center justify-between w-full p-2 text-gray-500 bg-white border border-transparent rounded-lg cursor-pointer dark:hover:text-gray-300 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                ${T("survey.builder.condition_none")}
                </label>
            </li>`)

        const question = $(e.currentTarget).parents("#question");
        const questionSlug = question.find("[data-slug]").data("slug");

        var conditions = JSON.parse(question.attr("conditions") ?? "[]")

        for (const [k, v] of Object.entries(questions)) {
            if (v.type == "description" || questionSlug == v.slug)
                continue;

            var hasChecked = conditions.findIndex(p => p.index == parentIndex && p.value == v.slug) > -1;

            ul.append(`<li>
                <input type="radio" id="${v.slug}" name="condition-${parentIndex}" value="${v.slug}" class="hidden peer" ${hasChecked ? "checked" : ""} required />
                <label for="${v.slug}" class="inline-flex items-center justify-between w-full p-2 text-gray-500 bg-white border-2 border-transparent rounded-lg cursor-pointer dark:hover:text-gray-300 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                ${v.title}
                </label>
            </li>`)
        }
    })

    const getAnswerComponent = (body) => {
        return `
            <div id="answer" draggable="false" class="mt-2 bg-gray-100 focus:outline-blue-600 rounded-md dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                <div class="flex items-start gap-2 p-2">
                    <div class="content flex-1 min-w-0 p-1 rounded-md focus:outline-blue-600" contenteditable="true">${body}</div>
                    <div class="shrink-0 flex items-center gap-1">
                    <button type="button" id="condition" class="bg-gray-300 dark:bg-gray-600 transition duration-200 rounded-full p-1 inline-flex items-center justify-center text-white hover:bg-gray-400 focus:outline-none">
                        <svg fill="currentColor" class="h-3 w-3" viewBox="0 0 32 32" id="icon" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><defs><style>.cls-1{fill:none;}</style></defs><title>align--horizontal-center</title><path d="M24,18H17V14h3a2.0025,2.0025,0,0,0,2-2V8a2.0025,2.0025,0,0,0-2-2H17V2H15V6H12a2.0025,2.0025,0,0,0-2,2v4a2.0025,2.0025,0,0,0,2,2h3v4H8a2.0025,2.0025,0,0,0-2,2v4a2.0025,2.0025,0,0,0,2,2h7v4h2V26h7a2.0025,2.0025,0,0,0,2-2V20A2.0025,2.0025,0,0,0,24,18ZM12,8h8v4H12ZM24,24H8V20H24Z"></path><rect id="_Transparent_Rectangle_" data-name="<Transparent Rectangle>" class="cls-1" width="32" height="32"></rect></g></svg> 
                    </button>
                    <div class="js-answer-drag-handle bg-slate-500 dark:bg-slate-600 transition duration-200 rounded-full p-1.5 inline-flex items-center justify-center text-white hover:bg-slate-600 focus:outline-none cursor-grab active:cursor-grabbing" draggable="true" title="${T("survey.builder.drag", "Sürükle")}">
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none">
                            <path d="M9 6h.01M9 12h.01M9 18h.01M15 6h.01M15 12h.01M15 18h.01" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <button type="button" class="remove bg-gray-300 dark:bg-gray-600 transition duration-200 rounded-full p-1 inline-flex items-center justify-center text-white hover:bg-gray-400 focus:outline-none">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    </div>
                </div>
                <div id="condition-dropdown" class="dropdown hidden z-20 w-auto origin-top-right bg-white rounded-lg border dark:border-gray-600 shadow-xl dark:bg-gray-800 mx-2 mb-2">
                    <ul class="p-2 space-y-3 text-sm text-gray-700 h-80 min-w-80 overflow-x-auto dark:text-gray-200">
                        
                    </ul>
                </div>
            </div>
        `
    }

    function createCheckableList(
        type,
        questionDummyText,
        answerDummyText,
        slug,
        answers = [],
        isHorizontal = false,
        isRequired = false,
        conditions = []
    ) {
        var generatedAnswers = "";
        for (const v of Object.values(answers))
            generatedAnswers += getAnswerComponent(v);

        const body = `
            <div contenteditable="true" data-slug="${slug}" class="bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>

            <div class="ml-8 my-3">
                <div id="answers">
                    ${generatedAnswers}
                </div>
                <div id="create-answer" contenteditable="true" class="focus:rounded-lg border-b border-gray-500 my-2 p-2 text-sm focus:outline-blue-600">${answerDummyText}</div>
            </div>        
        `;

        return getQuestionComponent(type, body, isRequired, conditions, true, isHorizontal);
    }

    function createSelectWithOptions(title, options, slug, isRequired = false, conditions = []) {
        return createCheckableList("select", title, "...", slug, options || [], false, isRequired, conditions);
    }

    function createRadioWithOptions(title, options, slug, isRequired = false, conditions = [], isHorizontal = false) {
        return createCheckableList("radio", title, "...", slug, options || [], isHorizontal, isRequired, conditions);
    }

    function createCheckboxWithOptions(title, options, slug, isRequired = false, conditions = []) {
        return createCheckableList("checkbox", title, "...", slug, options || [], false, isRequired, conditions);
    }

    function createRangeOptionList(min, max, suffix = "") {
        const arr = [];
        for (let i = min; i <= max; i++)
            arr.push(`${i}${suffix}`);
        return arr;
    }

    function sentimentAnswers(negative, positive, steps) {
        const n = (negative || "Beğenmedim").trim();
        const p = (positive || "Beğendim").trim();
        const s = Math.max(2, Math.min(7, parseInt(steps, 10) || 5));
        if (s <= 2) return [n, p];
        const palette = [
            "Kesinlikle Beğenmedim",
            "Çok Kötü",
            "Kötü",
            "Zayıf",
            "Biraz Olumsuz",
            T("survey.builder.neutral_value", "Kararsızım"),
            "Biraz Olumlu",
            "İyi",
            "Çok İyi",
            "Harika",
            "Bayıldım"
        ];
        const arr = [];
        const maxIdx = palette.length - 1;
        for (let i = 0; i < s; i++) {
            if (i === 0) {
                arr.push(n);
                continue;
            }
            if (i === s - 1) {
                arr.push(p);
                continue;
            }
            const ratio = i / (s - 1);
            const idx = Math.round(ratio * maxIdx);
            arr.push(palette[idx]);
        }
        return arr;
    }

    function createTextArea(questionDummyText, slug, isRequired = true) {
        const body = `<div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>`;

        return getQuestionComponent("textarea", body, isRequired);
    }

    function createShortText(questionDummyText, slug, isRequired, maxLength, conditions = []) {
        const ml = maxLength != null && maxLength !== "" ? maxLength : 255;
        const body = `
            <div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>
            <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                <label>${T("survey.builder.max_length")}
                    <input type="number" min="1" max="4000" class="js-q-maxlen ml-1 w-24 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 px-2 py-1 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" value="${ml}"/>
                </label>
            </div>`;
        return getQuestionComponent("short_text", body, isRequired, conditions, false, false, false);
    }

    function createEmail(questionDummyText, slug, isRequired, conditions = []) {
        const body = `<div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>`;
        return getQuestionComponent("email", body, isRequired, conditions, false, false, false);
    }

    function createUrl(questionDummyText, slug, isRequired, conditions = []) {
        const body = `<div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>`;
        return getQuestionComponent("url", body, isRequired, conditions, false, false, false);
    }

    function createPhone(questionDummyText, slug, isRequired, conditions = []) {
        const body = `<div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>`;
        return getQuestionComponent("phone", body, isRequired, conditions, false, false, false);
    }

    function createDate(questionDummyText, slug, isRequired, conditions = []) {
        const body = `<div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>`;
        return getQuestionComponent("date", body, isRequired, conditions, false, false, false);
    }

    function createTime(questionDummyText, slug, isRequired, conditions = []) {
        const body = `<div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>`;
        return getQuestionComponent("time", body, isRequired, conditions, false, false, false);
    }

    function createNumber(questionDummyText, slug, isRequired, numMin, numMax, numStep, conditions = []) {
        const body = `
            <div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>
            <div class="mt-1 grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs text-gray-600 dark:text-gray-400">
                <label>${T("survey.builder.min")} <input type="number" class="js-num-min ${baseInputClass}" value="${numMin != null && numMin !== "" ? numMin : ""}" placeholder="—"/></label>
                <label>${T("survey.builder.max")} <input type="number" class="js-num-max ${baseInputClass}" value="${numMax != null && numMax !== "" ? numMax : ""}" placeholder="—"/></label>
                <label>${T("survey.builder.step")} <input type="number" class="js-num-step ${baseInputClass}" value="${numStep != null && numStep !== "" ? numStep : ""}" placeholder="—"/></label>
            </div>`;
        return getQuestionComponent("number", body, isRequired, conditions, false, false, false);
    }

    function createScale(questionDummyText, slug, isRequired, scaleMin, scaleMax, scaleStep, labelLeft, labelRight, conditions = []) {
        const smin = scaleMin != null ? scaleMin : 1;
        const smax = scaleMax != null ? scaleMax : 5;
        const sstep = scaleStep != null && scaleStep !== "" ? scaleStep : 1;
        const body = `
            <div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>
            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400 mb-2">
                <label>${T("survey.builder.scale_from")} <input type="number" class="js-scale-min ${baseInputClass}" value="${smin}"/></label>
                <label>${T("survey.builder.scale_to")} <input type="number" class="js-scale-max ${baseInputClass}" value="${smax}"/></label>
                <label>${T("survey.builder.step")} <input type="number" class="js-scale-step ${baseInputClass}" value="${sstep}"/></label>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400">
                <label>${T("survey.builder.label_left")} <input type="text" class="js-scale-label-left ${baseInputClass}" value="${(labelLeft || "").replace(/"/g, '&quot;')}"/></label>
                <label>${T("survey.builder.label_right")} <input type="text" class="js-scale-label-right ${baseInputClass}" value="${(labelRight || "").replace(/"/g, '&quot;')}"/></label>
            </div>`;
        return getQuestionComponent("scale", body, isRequired, conditions, false, false, false);
    }

    function createSentimentScale(questionDummyText, slug, isRequired, negativeText, positiveText, steps, conditions = []) {
        const neg = negativeText || T("survey.builder.negative_value", "Beğenmedim");
        const pos = positiveText || T("survey.builder.positive_value", "Beğendim");
        const st = Math.max(2, Math.min(7, parseInt(steps, 10) || 5));
        const answers = sentimentAnswers(neg, pos, st);
        const body = `
            <div contenteditable="true" data-slug="${slug}" class="mb-3 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs text-gray-600 dark:text-gray-400 mb-2">
                <label>${T("survey.builder.negative_label", "Negatif")}
                    <input type="text" class="js-sent-neg ${baseInputClass}" value="${(neg || "").replace(/"/g, '&quot;')}"/>
                </label>
                <label>${T("survey.builder.positive_label", "Pozitif")}
                    <input type="text" class="js-sent-pos ${baseInputClass}" value="${(pos || "").replace(/"/g, '&quot;')}"/>
                </label>
                <label>${T("survey.builder.steps", "Adım")}
                    <input type="number" min="2" max="7" class="js-sent-steps ${baseInputClass}" value="${st}"/>
                </label>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">${answers.join(" • ")}</div>`;
        return getQuestionComponent("sentiment_scale", body, isRequired, conditions, false, false, false);
    }

    function createSection(titleHtml, subtitleHtml, slug, conditions = []) {
        const body = `
            <div contenteditable="true" data-slug="${slug}" class="mb-2 text-lg font-semibold bg-gray-50 dark:bg-gray-700 rounded-md px-4 py-2">${titleHtml}</div>
            <div contenteditable="true" data-section-sub="1" class="text-sm text-gray-600 dark:text-gray-400 rounded-md px-4 py-1 border border-dashed border-gray-300 dark:border-gray-600">${subtitleHtml || ""}</div>`;
        return getQuestionComponent("section", body, false, conditions, false, false, true);
    }

    function createDescription(questionDummyText, slug, subType = 0) {
        const body = `
            <div contenteditable="true" data-slug="${slug}" class="mb-3 min-h-14 bg-gray-100 focus:outline-blue-600 dark:bg-gray-700 rounded-md px-4 py-2">${questionDummyText}</div>
                
            <div class="rounded-t-lg bg-yellow-100 dark:bg-gray-900 p-2">
                <div class="col-span-4">
                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md outline-none focus:ring-blue-500 focus:border-blue-500 p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="0" ${subType == 0 ? "selected" : ""}>${T("survey.builder.desc_subtype.info")}</option>
                        <option value="1" ${subType == 1 ? "selected" : ""}>${T("survey.builder.desc_subtype.warning")}</option>
                        <option value="2" ${subType == 2 ? "selected" : ""}>${T("survey.builder.desc_subtype.success")}</option>
                        <option value="3" ${subType == 3 ? "selected" : ""}>${T("survey.builder.desc_subtype.danger")}</option>
                    </select>
                </div>
            </div>
        `;

        return getQuestionComponent("description", body);
    }

    const generate = () => {
        const questions = $(".questions #question");
        var result = [];
        questions.each(function () {
            $this = $(this);
            const qType = $this.data("type");
            const $titleEl = $this.find("[data-slug]").first();
            var slug = $titleEl.data("slug");
            if (!slug) slug = randomString(6);

            var isRequired = $this.find("input[type=checkbox]:eq(0)").prop("checked");
            var isHorizontal = (qType === "radio" || qType === "checkbox")
                ? $this.find("input[type=checkbox]:eq(1)").prop("checked")
                : false;

            var question = {
                title: clearText($titleEl.html().trim()),
                slug: slug,
                type: qType,
                isRequired: isRequired,
                isHorizontal: isHorizontal,
                subType: $this.find("select:eq(0)").val(),
                conditions: JSON.parse($this.attr("conditions") ?? "[]"),
                answers: []
            };

            if (qType === "section") {
                const subEl = $this.find("[data-section-sub]");
                question.subtitle = subEl.length ? clearText(subEl.html().trim()) : "";
            }

            if (qType === "short_text") {
                const ml = parseInt($this.find(".js-q-maxlen").val(), 10);
                question.maxLength = isNaN(ml) ? 255 : Math.min(4000, Math.max(1, ml));
            }

            if (qType === "number") {
                question.numMin = $this.find(".js-num-min").val();
                question.numMax = $this.find(".js-num-max").val();
                question.numStep = $this.find(".js-num-step").val();
            }

            if (qType === "scale") {
                question.scaleMin = parseFloat($this.find(".js-scale-min").val()) || 1;
                question.scaleMax = parseFloat($this.find(".js-scale-max").val()) || 5;
                question.scaleStep = parseFloat($this.find(".js-scale-step").val()) || 1;
                question.labelLeft = $this.find(".js-scale-label-left").val() || "";
                question.labelRight = $this.find(".js-scale-label-right").val() || "";
            }
            if (qType === "sentiment_scale") {
                question.negativeText = ($this.find(".js-sent-neg").val() || "").trim();
                question.positiveText = ($this.find(".js-sent-pos").val() || "").trim();
                question.sentimentSteps = Math.max(2, Math.min(7, parseInt($this.find(".js-sent-steps").val(), 10) || 5));
                question.answers = sentimentAnswers(question.negativeText, question.positiveText, question.sentimentSteps).map((x) => clearText(String(x)));
            }

            const answers = $this.find("#answers #answer");
            answers.each(function () {
                const clonedDom = $(this).clone().find("[contenteditable]")
                const answerContent = clonedDom.html().trim();
                question.answers.push(clearText(answerContent));
            });
            result.push(question);
        });
        $("form input[name=data]").val(JSON.stringify(result));
        window.__SURMEY_SURVEY_QUESTIONS = result;
        return result;
    };

    const prepareSurveyForEditing = function (formData, jsonData) {
        formData = JSON.parse(formData);
        $(".questions").empty();
        $("input[name=title]").val(formData.title);
        $("input[name=verifyPhone]").prop("checked", formData.verifyPhone);
        $("input[name=anonymous]").prop("checked", formData.anonymous);

        if (formData.photo)
            $("#cover-photo-result").css(
                "background-image",
                `url('/public/images/survey/${formData.photo}')`
            );

        $("textarea[name=about]").html(formData.about).tinymce()

        for (const [k, v] of Object.entries(JSON.parse(jsonData))) {
            var content = "";
            switch (v.type) {
                case "radio":
                case "checkbox":
                case "select":
                    content = createCheckableList(
                        v.type,
                        v.title,
                        "...",
                        v.slug,
                        v.answers || [],
                        !!v.isHorizontal,
                        !!v.isRequired,
                        v.conditions || []
                    );
                    break;
                case "textarea":
                    content = createTextArea(v.title, v.slug, !!v.isRequired);
                    break;
                case "short_text":
                    content = createShortText(v.title, v.slug, !!v.isRequired, v.maxLength, v.conditions || []);
                    break;
                case "email":
                    content = createEmail(v.title, v.slug, !!v.isRequired, v.conditions || []);
                    break;
                case "url":
                    content = createUrl(v.title, v.slug, !!v.isRequired, v.conditions || []);
                    break;
                case "phone":
                    content = createPhone(v.title, v.slug, !!v.isRequired, v.conditions || []);
                    break;
                case "date":
                    content = createDate(v.title, v.slug, !!v.isRequired, v.conditions || []);
                    break;
                case "time":
                    content = createTime(v.title, v.slug, !!v.isRequired, v.conditions || []);
                    break;
                case "number":
                    content = createNumber(v.title, v.slug, !!v.isRequired, v.numMin, v.numMax, v.numStep, v.conditions || []);
                    break;
                case "scale":
                    content = createScale(v.title, v.slug, !!v.isRequired, v.scaleMin, v.scaleMax, v.scaleStep, v.labelLeft, v.labelRight, v.conditions || []);
                    break;
                case "sentiment_scale":
                    content = createSentimentScale(v.title, v.slug, !!v.isRequired, v.negativeText, v.positiveText, v.sentimentSteps, v.conditions || []);
                    break;
                case "section":
                    content = createSection(v.title, v.subtitle || "", v.slug, v.conditions || []);
                    break;
                case "description":
                    content = createDescription(v.title, v.slug, v.subType != null ? v.subType : 0);
                    break;
            }
            if (content)
                $(".questions").append(content);
        }

        initTinymce()
    };

    function sectionMeta(data) {
        const blockMap = {};
        const slugToSection = {};
        const sectionSlugs = [];
        let activeSection = null;
        data.forEach((q) => {
            if (!q || !q.slug) return;
            if (q.type === "section") {
                activeSection = q.slug;
                sectionSlugs.push(q.slug);
                blockMap[activeSection] = [activeSection];
                slugToSection[q.slug] = q.slug;
                return;
            }
            if (activeSection) {
                blockMap[activeSection].push(q.slug);
                slugToSection[q.slug] = activeSection;
            } else {
                slugToSection[q.slug] = null;
            }
        });
        return { blockMap, slugToSection, sectionSlugs };
    }

    function conditionSatisfied(parentQ, cond) {
        if (!parentQ || !cond) return false;
        if (parentQ.type === "select") {
            const pv = $(`select[name="${parentQ.slug}"]`).val();
            return pv !== undefined && pv !== "" && String(pv) === String(cond.index);
        }
        if (parentQ.type === "radio") {
            const pr = $(`input[name="${parentQ.slug}"]:checked`).val();
            return pr !== undefined && String(pr) === String(cond.index);
        }
        if (parentQ.type === "checkbox")
            return $(`input[name="${parentQ.slug}${cond.index}"]`).is(":checked");
        return false;
    }

    function setSingleVisibility(slug, show) {
        const $t = $(`[data-slug='${slug}']`);
        if (!$t.length) return;
        if (show) {
            $t.show();
            return;
        }
        $t.hide();
        $t.find("input[type=checkbox],input[type=radio]").prop("checked", false);
        $t.find("select").val("");
        $t.find("input[type=text],textarea,input[type=email],input[type=number],input[type=date],input[type=time],input[type=tel],input[type=url]").val("");
        $t.find("input[type=range]").each(function () {
            const mn = $(this).attr("min");
            $(this).val(mn || "0");
        });
    }

    function setTargetVisibility(targetSlug, show, blockMap) {
        const block = (blockMap && blockMap[targetSlug]) ? blockMap[targetSlug] : [targetSlug];
        block.forEach((slug) => {
            const $t = $(`[data-slug='${slug}']`);
            if (show) {
                $t.show();
            } else {
                $t.hide();
                $t.find("input[type=checkbox],input[type=radio]").prop("checked", false);
                $t.find("select").val("");
                $t.find("input[type=text],textarea,input[type=email],input[type=number]").val("");
                $t.find("input[type=range]").each(function () {
                    const mn = $(this).attr("min");
                    $(this).val(mn || "0");
                });
            }
        });
    }

    window.syncSurveyBranching = function () {
        const data = window.__SURMEY_SURVEY_QUESTIONS;
        if (!data || !data.length) return;
        const { blockMap, slugToSection, sectionSlugs } = sectionMeta(data);
        const rulesByTarget = {};
        const bySlug = {};
        const visibility = {};

        data.forEach((q) => {
            if (!q || !q.slug) return;
            bySlug[q.slug] = q;
            visibility[q.slug] = true;
        });

        data.forEach((parentQ) => {
            if (!parentQ || !parentQ.conditions || !parentQ.conditions.length) return;
            parentQ.conditions.forEach((cond) => {
                if (!cond || !cond.value) return;
                if (!rulesByTarget[cond.value]) rulesByTarget[cond.value] = [];
                rulesByTarget[cond.value].push({ parentQ, cond });
            });
        });

        Object.keys(rulesByTarget).forEach((targetSlug) => {
            const rules = rulesByTarget[targetSlug];
            visibility[targetSlug] = rules.some((r) => conditionSatisfied(r.parentQ, r.cond));
        });

        Object.keys(visibility).forEach((slug) => {
            const parentSection = slugToSection[slug];
            if (!parentSection || parentSection === slug) return;
            if (visibility[parentSection] === false) visibility[slug] = false;
        });

        sectionSlugs.forEach((sectionSlug) => {
            const show = visibility[sectionSlug] !== false;
            setTargetVisibility(sectionSlug, show, blockMap);
        });

        Object.keys(visibility).forEach((slug) => {
            if (sectionSlugs.includes(slug)) return;
            setSingleVisibility(slug, visibility[slug] !== false);
        });
    };

    $(document).on(
        "change",
        ".preview-content input, .preview-content select, .generated input, .generated select",
        function () {
            window.syncSurveyBranching();
        }
    );

    const escapeAttr = (s) => {
        if (!s) return "";
        return String(s).replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/</g, "&lt;");
    };

    const renderFormEntry = (element) => {
        if (element.type === "section") {
            const sub = element.subtitle
                ? `<p class="mt-1 text-gray-600 dark:text-gray-400">${linkify(String(element.subtitle).replaceAll('\\"', '"'))}</p>`
                : "";
            return `
                <div data-slug="${escapeAttr(element.slug)}" class="survey-section border-b-2 border-blue-200/80 dark:border-blue-800 pb-4 mb-6 mt-2">
                    <h2 class="text-gray-900 dark:text-gray-100">${linkify(element.title.replaceAll('\\\"', '"'))}</h2>
                    ${sub}
                </div>`;
        }

        if (element.type === "description") {
            const types = ["info", "warning", "success", "danger"];
            const st = parseInt(element.subType, 10) || 0;
            return `
                    <div data-slug="${element.slug}" class="my-3 shadow-sm rounded-md p-3 alert-${types[st]}">
                        ${linkify(element.title.replaceAll('\\\"', '"'))}
                    </div>
                `;
        }

        let content = `
                <div data-slug="${element.slug}" class="rounded-lg bg-slate-50/30 border mb-3 border-gray-200 shadow-sm p-4 dark:border-gray-600 dark:bg-slate-900 preview-field"> 
                    <h1 class="flex text-clip border-b border-gray-200 pb-3 pt-1 dark:border-gray-600 mb-4">
                        ${linkify(element.title.replaceAll('\\\"', '"'))}  ${element.isRequired ? "<b class='ml-2 text-red-600'>*</b>" : ""}
                    </h1>
              `;

        switch (element.type) {
            case "radio":
            case "checkbox":
                let divClass = "inline";
                if (element.isHorizontal)
                    divClass = "grid grid-cols-1 gap-y-2 sm:flex sm:flex-wrap sm:gap-x-6";
                content += `<div class="${divClass}">`;
                element.answers.forEach((answer, i) => {
                    const arrayIndex = !element.hasOwnProperty("conditions") ? -1 : element.conditions.findIndex(p => p.index == i)
                    const hasCondition = arrayIndex > -1 ? element.conditions[arrayIndex].value : "none"
                    content += `
                  <div class="flex w-full sm:w-auto items-center mb-2 mx-1">
                      <input data-condition="${hasCondition}" data-change-tracker="true" id="link-${element.slug + i}" name="${element.type == "radio" ? element.slug : element.slug + i}" type="${element.type}" value="${i}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                      <label for="link-${element.slug + i}" class="ml-2 text-gray-900 dark:text-gray-300 break-words"><span class="preview-option-content">${linkify(answer.replaceAll('\\\"', '"'))}</span></label>
                  </div>`;
                });
                content += "</div>";
                break;

            case "select":
                content += `<select name="${element.slug}" class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-slate-900 p-2 text-sm">`;
                content += `<option value="">${"—"}</option>`;
                element.answers.forEach((answer, i) => {
                    content += `<option value="${i}">${linkify(answer.replaceAll('\\\"', '"'))}</option>`;
                });
                content += `</select>`;
                break;

            case "sentiment_scale":
                content += `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">`;
                element.answers.forEach((answer, i) => {
                    content += `
                        <label class="cursor-pointer">
                            <input type="radio" name="${element.slug}" value="${i}" class="sr-only peer"/>
                            <div class="rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm text-center text-gray-700 dark:text-gray-200 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-colors duration-150">
                                ${linkify(answer.replaceAll('\\\"', '"'))}
                            </div>
                        </label>`;
                });
                content += `</div>`;
                break;

            case "textarea":
                content += `<textarea maxlength="1000" name="${element.slug}" class="w-full rounded-lg border mb-3 text-gray-900 dark:text-gray-50 border-gray-200 shadow-sm bg-white p-2 focus:outline-blue-500 dark:focus:outline-blue-600 dark:border-gray-600 dark:bg-slate-900"></textarea>`;
                break;

            case "short_text": {
                const ml = element.maxLength ? Math.min(4000, parseInt(element.maxLength, 10) || 255) : 255;
                content += `<input type="text" name="${element.slug}" maxlength="${ml}" class="w-full rounded-lg border text-gray-900 dark:text-gray-50 border-gray-200 shadow-sm bg-white p-2 dark:bg-slate-900 dark:border-gray-600"/>`;
                break;
            }

            case "email":
                content += `<input type="email" name="${element.slug}" class="w-full rounded-lg border text-gray-900 dark:text-gray-50 border-gray-200 shadow-sm bg-white p-2 dark:bg-slate-900 dark:border-gray-600"/>`;
                break;

            case "url":
                content += `<input type="url" name="${element.slug}" placeholder="https://…" class="w-full rounded-lg border text-gray-900 dark:text-gray-50 border-gray-200 shadow-sm bg-white p-2 dark:bg-slate-900 dark:border-gray-600"/>`;
                break;

            case "phone":
                content += `<input type="tel" name="${element.slug}" placeholder="${T("survey.builder.phone_placeholder", "05xx xxx xx xx")}" class="w-full rounded-lg border text-gray-900 dark:text-gray-50 border-gray-200 shadow-sm bg-white p-2 dark:bg-slate-900 dark:border-gray-600"/>`;
                break;

            case "date":
                content += `<input type="date" name="${element.slug}" class="w-full rounded-lg border text-gray-900 dark:text-gray-50 border-gray-200 shadow-sm bg-white p-2 dark:bg-slate-900 dark:border-gray-600"/>`;
                break;

            case "time":
                content += `<input type="time" name="${element.slug}" class="w-full rounded-lg border text-gray-900 dark:text-gray-50 border-gray-200 shadow-sm bg-white p-2 dark:bg-slate-900 dark:border-gray-600"/>`;
                break;

            case "number": {
                let minA = element.numMin !== undefined && element.numMin !== "" ? ` min="${escapeAttr(element.numMin)}"` : "";
                let maxA = element.numMax !== undefined && element.numMax !== "" ? ` max="${escapeAttr(element.numMax)}"` : "";
                let stepA = element.numStep !== undefined && element.numStep !== "" ? ` step="${escapeAttr(element.numStep)}"` : "";
                content += `<input type="number" name="${element.slug}" class="w-full rounded-lg border text-gray-900 dark:text-gray-50 border-gray-200 shadow-sm bg-white p-2 dark:bg-slate-900 dark:border-gray-600"${minA}${maxA}${stepA}/>`;
                break;
            }

            case "scale": {
                const smin = element.scaleMin != null ? parseFloat(element.scaleMin) : 1;
                const smax = element.scaleMax != null ? parseFloat(element.scaleMax) : 5;
                const sstep = element.scaleStep != null ? parseFloat(element.scaleStep) : 1;
                const ll = element.labelLeft ? `<span class="text-xs text-gray-600 dark:text-gray-400 mr-2">${escapeAttr(element.labelLeft)}</span>` : "";
                const lr = element.labelRight ? `<span class="text-xs text-gray-600 dark:text-gray-400 ml-2">${escapeAttr(element.labelRight)}</span>` : "";
                content += `<div class="flex flex-wrap items-center gap-2">${ll}<input type="range" name="${element.slug}" min="${smin}" max="${smax}" step="${sstep}" value="${smin}" class="js-scale-range flex-1 min-w-[8rem]"/>${lr}<span class="js-scale-readout text-sm font-medium tabular-nums">${smin}</span></div>`;
                break;
            }

            default:
                content += "";
        }

        content += "</div>";
        return content;
    };

    window.generateSurvey = generate;
    window.renderFormEntry = renderFormEntry;
    window.prepareSurveyForEditing = prepareSurveyForEditing;
    function scrollToQuestion($el) {
        if (!$el || !$el.length) return;
        $el[0].scrollIntoView({ behavior: "smooth", block: "center" });
    }

    function assignSectionIndexes($container) {
        if (!$container || !$container.length) return;
        let current = -1;
        $container.children().each(function () {
            const $el = $(this);
            if ($el.hasClass("survey-section")) current++;
            if (current < 0) current = 0;
            $el.attr("data-section-idx", String(current));
        });
    }

    function setupSectionPager($container) {
        if (!$container || !$container.length) return;
        const sectionCount = Math.max(0, $container.find(".survey-section").length);
        if (sectionCount <= 0) return;

        assignSectionIndexes($container);

        if ($container.find(".js-section-pager").length) return;

        const pager = $(`
            <div class="js-section-pager mt-6 flex items-center justify-between gap-3 border-t pt-4 dark:border-gray-700">
                <button type="button" class="js-sec-back rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-slate-800">${T("survey.builder.back", "Geri")}</button>
                <div class="js-sec-progress text-sm text-gray-600 dark:text-gray-300"></div>
                <button type="button" class="js-sec-next rounded-md bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-500">${T("survey.builder.next", "İleri")}</button>
            </div>
        `);
        $container.append(pager);

        function getMaxSectionIdx() {
            const idxs = $container.children("[data-section-idx]").map((_, el) => parseInt($(el).attr("data-section-idx"), 10) || 0).get();
            const max = idxs.length ? Math.max.apply(null, idxs) : 0;
            return isFinite(max) ? max : 0;
        }

        function isSectionVisible(idx) {
            const $sec = $container.children(`.survey-section[data-section-idx='${idx}']`);
            if (!$sec.length) return idx === 0;
            return $sec.is(":visible");
        }

        function findNextVisible(from, dir) {
            const max = getMaxSectionIdx();
            if (dir === 0) return from;
            let i = from;
            while (true) {
                i += dir;
                if (i < 0 || i > max) return from;
                if (isSectionVisible(i)) return i;
            }
        }

        function renderSection(idx) {
            $container.children("[data-section-idx]").each(function () {
                const $el = $(this);
                const si = parseInt($el.attr("data-section-idx"), 10) || 0;
                $el.toggle(si === idx);
            });
            pager.show();
            pager.find(".js-sec-back").prop("disabled", idx <= 0).toggleClass("opacity-50", idx <= 0);
            const max = getMaxSectionIdx();
            const nextIdx = findNextVisible(idx, +1);
            const atEnd = nextIdx === idx && idx >= max;
            pager.find(".js-sec-next").text(atEnd ? T("survey.builder.finish", "Bitir") : T("survey.builder.next", "İleri"));
            pager.find(".js-sec-progress").text(`${idx + 1}/${max + 1}`);
            $container.data("section-idx", idx);
            if (window.syncSurveyBranching) window.syncSurveyBranching();
        }

        pager.on("click", ".js-sec-back", function () {
            const idx = parseInt($container.data("section-idx"), 10) || 0;
            renderSection(findNextVisible(idx, -1));
        });
        pager.on("click", ".js-sec-next", function () {
            const idx = parseInt($container.data("section-idx"), 10) || 0;
            const max = getMaxSectionIdx();
            const nextIdx = findNextVisible(idx, +1);
            if (nextIdx === idx && idx >= max) return;
            renderSection(nextIdx);
        });

        renderSection(0);
    }

    function setupBuilderSectionGroups() {
        const $container = $(".questions");
        if (!$container.length) return;
        $container.find(".js-unsectioned-dropzone").remove();
        $container.find(".js-section-card").each(function () {
            $(this).replaceWith($(this).children(".js-section-card-body").children("#question"));
        });

        const $all = $container.children("#question");
        if (!$all.length) return;

        let $currentCardBody = null;
        let idx = -1;
        $all.each(function () {
            const $q = $(this);
            if ($q.data("type") === "section") {
                idx++;
                const card = $(`
                    <div class="js-section-card rounded-xl border-2 border-dashed border-blue-300/90 dark:border-blue-700/80 bg-blue-50/45 dark:bg-blue-900/15 p-2 mb-3 ring-1 ring-blue-200/50 dark:ring-blue-800/40" data-section-card-idx="${idx}">
                        <div class="js-section-card-body min-h-[40px]"></div>
                    </div>
                `);
                $container.append(card);
                $currentCardBody = card.find(".js-section-card-body");
            }
            if ($currentCardBody) $currentCardBody.append($q);
            else $container.append($q);
        });

        $container.find("#question").attr("draggable", "false");
        $container.find("#question .js-question-drag-handle").attr("draggable", "true");
        const dropzone = $(`
            <div class="js-unsectioned-dropzone mb-3 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50/70 dark:bg-slate-800/50 px-3 py-2 text-xs text-gray-500 dark:text-gray-300">
                ${T("survey.builder.unsectioned_zone", "Bölümsüz alan (soruyu buraya bırak)")}
            </div>
        `);
        $container.prepend(dropzone);
    }

    window.__SURMEY_setupSectionPager = setupSectionPager;
    window.__SURMEY_assignSectionIndexes = assignSectionIndexes;
    function refreshPreviewIfVisible() {
        if (!$(".preview-content").is(":visible")) return;
        $("#preview").trigger("click");
    }

    $(document).on("click", ".remove", function (event) {

        $parent = $(this).parent()

        if ($parent.attr("id") != "question")
            $parent = $parent.parent()

        $parent.remove();
        setupBuilderSectionGroups();
        refreshPreviewIfVisible();
    });

    $(document).on("click", ".up, .down", function (event) {

        $parent = $(this).parent()

        if ($parent.attr("id") != "question")
            $parent = $parent.parent()

        const isUpper = $(this).hasClass("up");

        $v = isUpper ? $parent.prev() : $parent.next()

        if ($parent.attr("id") != "answer")
            if (!$v.is("[data-type]"))
                return;

        if (isUpper)
            $parent.insertBefore($v)
        else
            $parent.insertAfter($v)
        setupBuilderSectionGroups();
        refreshPreviewIfVisible();
    });

    const DRAG_THEME = {
        helperClass: "my-1.5 h-2 rounded-full bg-gradient-to-r from-blue-500 to-cyan-400 shadow-[0_0_0_2px_rgba(59,130,246,.2),0_8px_18px_rgba(37,99,235,.35)]",
        indicatorClass: "ring-4 ring-blue-400/70 border-2 border-blue-400 shadow-2xl shadow-blue-500/25",
        indicatorClearClass: "ring-2 ring-4 ring-blue-400/80 ring-blue-400/70 border border-2 border-blue-400 border-t-4 border-t-blue-500 shadow-xl shadow-2xl shadow-blue-500/25"
    };

    let __dragQuestionSlug = null;
    let __dragQuestionEl = null;
    let __dragGhostEl = null;
    let __dropHelperEl = null;
    let __dropTargetEl = null;
    let __dropPosition = "before";
    let __lastDropTargetEl = null;
    let __lastDropPosition = null;
    function ensureDropHelper() {
        if (__dropHelperEl) return __dropHelperEl;
        __dropHelperEl = document.createElement("div");
        __dropHelperEl.className = `js-drop-helper ${DRAG_THEME.helperClass}`;
        return __dropHelperEl;
    }
    function clearDropHelper() {
        if (__dropHelperEl && __dropHelperEl.parentNode)
            __dropHelperEl.parentNode.removeChild(__dropHelperEl);
        __dropTargetEl = null;
        __lastDropTargetEl = null;
        __lastDropPosition = null;
    }
    function setDraggingUiState(on) {
        const $root = $(".questions");
        if (on) {
            $root.addClass("is-dragging");
            return;
        }
        $root.removeClass("is-dragging");
    }
    function clearQuestionDropIndicators() {
        $(".questions #question, .questions .js-section-card-body, .questions .js-unsectioned-dropzone")
            .removeClass(DRAG_THEME.indicatorClearClass);
    }
    function animateDroppedElement($el) {
        if (!$el || !$el.length) return;
        $el.removeClass("surmey-drop-settle");
        void $el[0].offsetWidth;
        $el.addClass("surmey-drop-settle");
        setTimeout(() => $el.removeClass("surmey-drop-settle"), 260);
    }
    function clearQuestionCardStyling($el) {
        if (!$el || !$el.length) return;
        $el.removeClass(`opacity-60 scale-[0.99] ${DRAG_THEME.indicatorClearClass}`);
    }
    function isQuestionDragActive() {
        return !!(__dragQuestionEl && __dragQuestionEl.length);
    }
    function isAnswerDragActive() {
        return !!(__dragAnswerEl && __dragAnswerEl.length);
    }
    $(document).on("mousedown", ".questions .js-question-drag-handle", function () {
        const $q = $(this).closest("#question");
        __dragQuestionSlug = $q.find("[data-slug]").first().data("slug");
        __dragQuestionEl = $q;
    });
    $(document).on("dragstart", ".questions .js-question-drag-handle", function (e) {
        const $q = $(this).closest("#question");
        __dragQuestionSlug = $q.find("[data-slug]").first().data("slug");
        __dragQuestionEl = $q;
        $q.addClass("opacity-60 scale-[0.99]");
        setDraggingUiState(true);
        e.stopPropagation();
        if (e.originalEvent && e.originalEvent.dataTransfer) {
            e.originalEvent.dataTransfer.setData("text/plain", String(__dragQuestionSlug || ""));
            e.originalEvent.dataTransfer.effectAllowed = "move";
            const ghost = $q[0].cloneNode(true);
            ghost.style.position = "fixed";
            ghost.style.top = "-9999px";
            ghost.style.left = "-9999px";
            ghost.style.width = `${Math.max(260, $q.outerWidth() || 260)}px`;
            ghost.style.opacity = "0.9";
            ghost.style.transform = "scale(0.98)";
            ghost.style.boxShadow = "0 12px 30px rgba(0,0,0,.25)";
            ghost.style.border = "2px solid rgba(59,130,246,.6)";
            ghost.style.borderRadius = "12px";
            ghost.style.pointerEvents = "none";
            document.body.appendChild(ghost);
            __dragGhostEl = ghost;
            e.originalEvent.dataTransfer.setDragImage(ghost, 24, 18);
        }
    });
    $(document).on("dragend", ".questions .js-question-drag-handle, .questions #question", function () {
        if (__dragQuestionEl && __dragQuestionEl.length)
            __dragQuestionEl.removeClass("opacity-60 scale-[0.99]");
        clearQuestionDropIndicators();
        clearDropHelper();
        setDraggingUiState(false);
        if (__dragGhostEl) {
            __dragGhostEl.remove();
            __dragGhostEl = null;
        }
        __dragQuestionSlug = null;
        __dragQuestionEl = null;
    });
    $(document).on("dragover", ".questions #question, .questions .js-section-card-body", function (e) {
        e.preventDefault();
        if (e.originalEvent && e.originalEvent.dataTransfer)
            e.originalEvent.dataTransfer.dropEffect = "move";
        if (!isQuestionDragActive() || isAnswerDragActive()) return;
        const $t = $(this);
        if ($t.is("#question") && !$t.is(__dragQuestionEl)) {
            const rect = this.getBoundingClientRect();
            const y = e.originalEvent ? e.originalEvent.clientY : 0;
            __dropPosition = (y > rect.top + rect.height / 2) ? "after" : "before";
            __dropTargetEl = this;
            const helper = ensureDropHelper();
            if (__lastDropTargetEl !== this || __lastDropPosition !== __dropPosition) {
                if (__dropPosition === "before")
                    this.parentNode.insertBefore(helper, this);
                else
                    this.parentNode.insertBefore(helper, this.nextSibling);
                __lastDropTargetEl = this;
                __lastDropPosition = __dropPosition;
            }
        } else if ($t.hasClass("js-section-card-body")) {
            __dropTargetEl = this;
            __dropPosition = "inside";
            const helper = ensureDropHelper();
            if (__lastDropTargetEl !== this || __lastDropPosition !== "inside") {
                this.appendChild(helper);
                __lastDropTargetEl = this;
                __lastDropPosition = "inside";
            }
        } else {
            clearDropHelper();
        }
    });
    $(document).on("dragenter", ".questions #question, .questions .js-section-card-body, .questions .js-unsectioned-dropzone", function () {
        if (!isQuestionDragActive() || isAnswerDragActive()) return;
        clearQuestionDropIndicators();
        $(this).addClass(DRAG_THEME.indicatorClass);
    });
    $(document).on("dragleave", ".questions #question, .questions .js-section-card-body, .questions .js-unsectioned-dropzone", function (e) {
        if (!isQuestionDragActive() || isAnswerDragActive()) return;
        if ($(this).has(e.relatedTarget).length) return;
        $(this).removeClass(DRAG_THEME.indicatorClearClass);
    });
    $(document).on("drop", ".questions #question", function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (__dragAnswerId) return;
        $(this).removeClass(DRAG_THEME.indicatorClearClass);
        if (!isQuestionDragActive() || isAnswerDragActive()) return;
        const $drag = __dragQuestionEl;
        const $target = $(this);
        if ($drag.length && $target.length && !$drag.is($target)) {
            const rect = this.getBoundingClientRect();
            const y = e.originalEvent ? e.originalEvent.clientY : rect.top;
            const placeAfter = y > rect.top + rect.height / 2;
            if (placeAfter) $drag.insertAfter($target);
            else $drag.insertBefore($target);
            clearQuestionCardStyling($drag);
            animateDroppedElement($drag);
        }
        clearDropHelper();
        setupBuilderSectionGroups();
        refreshPreviewIfVisible();
    });
    $(document).on("drop", ".questions .js-section-card-body", function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (__dragAnswerId) return;
        $(this).removeClass(DRAG_THEME.indicatorClearClass);
        if (!isQuestionDragActive() || isAnswerDragActive()) return;
        const $drag = __dragQuestionEl;
        if ($drag.length) {
            $(this).append($drag);
            clearQuestionCardStyling($drag);
            animateDroppedElement($drag);
        }
        clearDropHelper();
        setupBuilderSectionGroups();
        refreshPreviewIfVisible();
    });
    $(document).on("drop", ".questions .js-unsectioned-dropzone", function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (__dragAnswerId) return;
        $(this).removeClass(DRAG_THEME.indicatorClearClass);
        if (!__dragQuestionEl || !__dragQuestionEl.length) return;
        const $drag = __dragQuestionEl;
        if (!$drag.length) return;
        const $firstSectionQuestion = $(".questions #question[data-type='section']").first();
        if ($firstSectionQuestion.length) $drag.insertBefore($firstSectionQuestion);
        else $(".questions").append($drag);
        clearQuestionCardStyling($drag);
        animateDroppedElement($drag);
        clearDropHelper();
        setupBuilderSectionGroups();
        refreshPreviewIfVisible();
    });

    let __dragAnswerId = null;
    let __dragAnswerEl = null;
    let __dragAnswerGhostEl = null;
    let __answerDropHelperEl = null;
    let __answerDropTargetEl = null;
    let __answerDropPosition = "before";
    let __lastAnswerDropTargetEl = null;
    let __lastAnswerDropPosition = null;
    function ensureAnswerDropHelper() {
        if (__answerDropHelperEl) return __answerDropHelperEl;
        __answerDropHelperEl = document.createElement("div");
        __answerDropHelperEl.className = `js-answer-drop-helper ${DRAG_THEME.helperClass}`;
        return __answerDropHelperEl;
    }
    function clearAnswerDropHelper() {
        if (__answerDropHelperEl && __answerDropHelperEl.parentNode)
            __answerDropHelperEl.parentNode.removeChild(__answerDropHelperEl);
        __answerDropTargetEl = null;
        __lastAnswerDropTargetEl = null;
        __lastAnswerDropPosition = null;
    }
    function clearAnswerDropIndicators() {
        $(".questions #answers #answer")
            .removeClass(DRAG_THEME.indicatorClearClass);
    }
    $(document).on("dragstart", ".questions #answer .js-answer-drag-handle", function (e) {
        const $a = $(this).closest("#answer");
        if (!$a.attr("data-answer-id")) $a.attr("data-answer-id", randomString(10));
        __dragAnswerId = $a.attr("data-answer-id");
        __dragAnswerEl = $a;
        $a.addClass("opacity-60");
        if (e.originalEvent && e.originalEvent.dataTransfer) {
            e.originalEvent.dataTransfer.setData("text/plain", String(__dragAnswerId || ""));
            e.originalEvent.dataTransfer.effectAllowed = "move";
            const ghost = $a[0].cloneNode(true);
            ghost.style.position = "fixed";
            ghost.style.top = "-9999px";
            ghost.style.left = "-9999px";
            ghost.style.width = `${Math.max(220, $a.outerWidth() || 220)}px`;
            ghost.style.opacity = "0.9";
            ghost.style.boxShadow = "0 10px 24px rgba(0,0,0,.2)";
            ghost.style.border = "2px solid rgba(59,130,246,.55)";
            ghost.style.borderRadius = "10px";
            ghost.style.pointerEvents = "none";
            document.body.appendChild(ghost);
            __dragAnswerGhostEl = ghost;
            e.originalEvent.dataTransfer.setDragImage(ghost, 18, 14);
        }
    });
    $(document).on("dragend", ".questions #answer .js-answer-drag-handle, .questions #answer", function () {
        if (__dragAnswerEl && __dragAnswerEl.length)
            __dragAnswerEl.removeClass("opacity-60");
        clearAnswerDropIndicators();
        clearAnswerDropHelper();
        if (__dragAnswerGhostEl) {
            __dragAnswerGhostEl.remove();
            __dragAnswerGhostEl = null;
        }
        __dragAnswerId = null;
        __dragAnswerEl = null;
    });
    $(document).on("dragover", ".questions #answers #answer", function (e) {
        if (!isAnswerDragActive() || isQuestionDragActive()) return;
        e.preventDefault();
        if (e.originalEvent && e.originalEvent.dataTransfer)
            e.originalEvent.dataTransfer.dropEffect = "move";
        const $t = $(this);
        if ($t.is(__dragAnswerEl)) return;
        const rect = this.getBoundingClientRect();
        const y = e.originalEvent ? e.originalEvent.clientY : rect.top;
        __answerDropPosition = (y > rect.top + rect.height / 2) ? "after" : "before";
        __answerDropTargetEl = this;
        const helper = ensureAnswerDropHelper();
        if (__lastAnswerDropTargetEl !== this || __lastAnswerDropPosition !== __answerDropPosition) {
            if (__answerDropPosition === "before")
                this.parentNode.insertBefore(helper, this);
            else
                this.parentNode.insertBefore(helper, this.nextSibling);
            __lastAnswerDropTargetEl = this;
            __lastAnswerDropPosition = __answerDropPosition;
        }
    });
    $(document).on("dragenter", ".questions #answers #answer", function () {
        if (!isAnswerDragActive() || isQuestionDragActive()) return;
        clearAnswerDropIndicators();
        $(this).addClass(DRAG_THEME.indicatorClass);
    });
    $(document).on("dragleave", ".questions #answers #answer", function (e) {
        if (!isAnswerDragActive() || isQuestionDragActive()) return;
        if ($(this).has(e.relatedTarget).length) return;
        $(this).removeClass(DRAG_THEME.indicatorClearClass);
    });
    $(document).on("drop", ".questions #answers #answer", function (e) {
        e.preventDefault();
        e.stopPropagation();
        clearAnswerDropIndicators();
        if (!__dragAnswerEl || !__dragAnswerEl.length) return;
        const $drag = __dragAnswerEl;
        const $target = $(this);
        if ($drag.length && $target.length && !$drag.is($target)) {
            if (__answerDropTargetEl) {
                if (__answerDropPosition === "after")
                    $drag.insertAfter($(__answerDropTargetEl));
                else
                    $drag.insertBefore($(__answerDropTargetEl));
            } else {
                $drag.insertBefore($target);
            }
            $drag.removeClass("opacity-60");
            animateDroppedElement($drag);
        }
        clearAnswerDropHelper();
        refreshPreviewIfVisible();
    });
    // NOTE: No generic ".questions" drop handler on purpose.
    // Specific drop targets handle placement to avoid duplicate move
    // from event bubbling, which caused disappearing/jumping cards.

    $(document).on("click", "#preview", function (event) {
        const data = generate();
        window.__SURMEY_SURVEY_QUESTIONS = data;
        $(".preview-content").html("");
        $(".preview-content").addClass("max-w-2xl mx-auto bg-white dark:bg-slate-900 rounded-xl shadow-lg ring-1 ring-gray-900/5 p-6 my-2 preview-form");
        data.forEach((element) =>
            $(".preview-content").append(renderFormEntry(element))
        )

        const { blockMap } = sectionMeta(data);
        data.forEach(element => {
            if (!element.conditions) return;
            element.conditions.forEach(condition => setTargetVisibility(condition.value, false, blockMap))
        })
        window.syncSurveyBranching();
        setupSectionPager($(".preview-content"));
    })

    $(document).on("keydown", "#create-answer", function (event) {
        if (!event.shiftKey && event.which == 13) {
            $(this).prev("#answers").append(getAnswerComponent($(this).text()));

            $(event.target).text("...");
            window.getSelection().selectAllChildren(event.target);

            initTinymce()

            return false;
        }
    })

    $(document).on("click", "#survey-crud [data-type]", function (event) {
        const type = $(this).data("type");
        const questionDummyText = $(this).data("question-text");
        const answerDummyText = $(this).attr("answer-text");
        var content = "";
        var slug = randomString(6);
        switch (type) {
            case "radio":
            case "checkbox":
            case "select":
                content = createCheckableList(
                    type,
                    questionDummyText,
                    answerDummyText,
                    slug
                );
                break;
            case "textarea":
                content = createTextArea(questionDummyText, slug);
                break;
            case "short_text":
                content = createShortText(questionDummyText, slug, false, 255);
                break;
            case "email":
                content = createEmail(questionDummyText, slug, false);
                break;
            case "url":
                content = createUrl(questionDummyText, slug, false);
                break;
            case "phone":
                content = createPhone(questionDummyText, slug, false);
                break;
            case "date":
                content = createDate(questionDummyText, slug, false);
                break;
            case "time":
                content = createTime(questionDummyText, slug, false);
                break;
            case "number":
                content = createNumber(questionDummyText, slug, false, "", "", "");
                break;
            case "scale":
                content = createScale(questionDummyText, slug, false, 1, 5, 1, "", "");
                break;
            case "section":
                content = createSection(questionDummyText, "", slug);
                break;
            case "description":
                content = createDescription(questionDummyText, slug);
                break;
            case "preset_star":
                content = createRadioWithOptions(
                    T("survey.builder.star_rating", "Yıldız değerlendirme"),
                    ["★", "★★", "★★★", "★★★★", "★★★★★"],
                    slug,
                    true,
                    [],
                    true
                );
                break;
            case "preset_like_dislike":
                content = createSentimentScale(T("survey.builder.like_dislike_scale", "Beğeni ölçeği"), slug, true, T("survey.builder.negative_value", "Beğenmedim"), T("survey.builder.positive_value", "Beğendim"), 5);
                break;
            case "preset_single_choice_scale":
                content = createSingleChoiceScale(T("survey.builder.single_choice_scale", "Tek seçimli ölçek"), slug, 1, 10, true);
                break;
        }

        if (content) {
            $(".questions").append(content);
            const $added = $(".questions #question").last();
            scrollToQuestion($added);
            setupBuilderSectionGroups();
            refreshPreviewIfVisible();
        }

        initTinymce()
    });

    const DRAFT_KEY = "surmey_survey_draft_v1";
    function saveSurveyDraft() {
        if (!window.__SURMEY_SURVEY_CREATE) return;
        try {
            const title = $("input[name=title]").val();
            const about = typeof tinymce !== "undefined" && tinymce.get("about") ? tinymce.get("about").getContent() : ($("textarea[name=about]").val() || "");
            const verifyPhone = $("input[name=verifyPhone]").prop("checked");
            const anonymous = $("input[name=anonymous]").prop("checked");
            const questions = generate();
            localStorage.setItem(DRAFT_KEY, JSON.stringify({
                form: { title, about, verifyPhone, anonymous, photo: "" },
                questions
            }));
        } catch (e) { }
    }
    let _draftTimer;
    $("body").on("change input", "form[action*='/surveys/apply'] input, form[action*='/surveys/apply'] textarea", function () {
        clearTimeout(_draftTimer);
        _draftTimer = setTimeout(saveSurveyDraft, 600);
    });
    $("body").on("keyup blur", "form[action*='/surveys/apply'] [contenteditable]", function () {
        clearTimeout(_draftTimer);
        _draftTimer = setTimeout(saveSurveyDraft, 600);
    });

    function applyTemplate(which) {
        const $questions = $(".questions");
        const existing = generate();
        const Q = T("survey.builder.new_question");
        const toAppend = [];
        const rs = () => randomString(6);
        if (which === "satisfaction") {
            toAppend.push(createSection(T("survey.builder.template_satisfaction"), "", rs()));
            toAppend.push(createScale("Genel memnuniyetinizi puanlayın", rs(), true, 1, 10, 1, "Çok kötü", "Mükemmel"));
            toAppend.push(createRadioWithOptions("Tekrar tercih eder misiniz?", ["Evet", "Hayır", "Kararsız"], rs(), true));
            toAppend.push(createTextArea("Eklemek istediğiniz bir yorum var mı?", rs(), false));
        } else if (which === "contact") {
            toAppend.push(createSection(T("survey.builder.template_contact"), "", rs()));
            toAppend.push(createShortText("Ad Soyad", rs(), true, 120));
            toAppend.push(createEmail("E-posta", rs(), true));
            toAppend.push(createPhone("Telefon", rs(), false));
            toAppend.push(createUrl("Web sitesi (varsa)", rs(), false));
            toAppend.push(createTextArea("Mesajınız", rs(), false));
        } else if (which === "event_registration") {
            toAppend.push(createSection(T("survey.builder.template_event_registration"), "", rs()));
            toAppend.push(createShortText("Ad Soyad", rs(), true, 120));
            toAppend.push(createEmail("E-posta", rs(), true));
            toAppend.push(createPhone("Telefon", rs(), false));
            toAppend.push(createSelectWithOptions("Katılım türü", ["Yüz yüze", "Online"], rs(), true));
            toAppend.push(createSelectWithOptions("Tişört bedeni", ["XS", "S", "M", "L", "XL"], rs(), false));
            toAppend.push(createTextArea("Notunuz / özel ihtiyaç", rs(), false));
        } else if (which === "customer_feedback") {
            toAppend.push(createSection(T("survey.builder.template_customer_feedback"), "", rs()));
            toAppend.push(createScale("Genel memnuniyetinizi puanlayın", rs(), true, 1, 10, 1, "Çok kötü", "Mükemmel"));
            toAppend.push(createSelectWithOptions("Hangi kanaldan bize ulaştınız?", ["Google", "Sosyal medya", "Tavsiye", "Diğer"], rs(), false));
            toAppend.push(createCheckboxWithOptions("En çok hangi özellikleri kullandınız?", ["Hız", "Raporlar", "Şablonlar", "Kolay kullanım", "Diğer"], rs(), false));
            toAppend.push(createTextArea("Sizi en çok ne memnun etti?", rs(), false));
            toAppend.push(createTextArea("Geliştirmemizi istediğiniz bir şey var mı?", rs(), false));
        } else if (which === "employee_pulse") {
            toAppend.push(createSection(T("survey.builder.template_employee_pulse"), "", rs()));
            toAppend.push(createScale("Bu hafta iş yüküm yönetilebilir düzeydeydi.", rs(), true, 1, 5, 1, "Katılmıyorum", "Katılıyorum"));
            toAppend.push(createScale("Ekip içi iletişim iyiydi.", rs(), true, 1, 5, 1, "Katılmıyorum", "Katılıyorum"));
            toAppend.push(createScale("Yöneticimden gerekli desteği aldım.", rs(), true, 1, 5, 1, "Katılmıyorum", "Katılıyorum"));
            toAppend.push(createTextArea("Bu hafta seni zorlayan konu neydi?", rs(), false));
            toAppend.push(createTextArea("Bir önerin var mı?", rs(), false));
        } else if (which === "lead_capture") {
            toAppend.push(createSection(T("survey.builder.template_lead_capture"), "", rs()));
            toAppend.push(createShortText("Şirket adı", rs(), true, 120));
            toAppend.push(createShortText("Ad Soyad", rs(), true, 120));
            toAppend.push(createEmail("E-posta", rs(), true));
            toAppend.push(createPhone("Telefon", rs(), false));
            toAppend.push(createSelectWithOptions("İlgilendiğiniz ürün", ["Surmey", "Entegrasyon", "Raporlama", "Diğer"], rs(), true));
            toAppend.push(createTextArea("Kısaca ihtiyacınızı anlatın", rs(), false));
        } else if (which === "preset_star") {
            toAppend.push(createRadioWithOptions(
                T("survey.builder.star_rating", "Yıldız değerlendirme"),
                ["★", "★★", "★★★", "★★★★", "★★★★★"],
                rs(),
                true,
                [],
                true
            ));
        } else if (which === "preset_like_dislike") {
            toAppend.push(createSentimentScale(T("survey.builder.like_dislike_scale", "Beğeni ölçeği"), rs(), true, T("survey.builder.negative_value", "Beğenmedim"), T("survey.builder.positive_value", "Beğendim"), 5));
        } else if (which === "preset_single_choice_scale") {
            toAppend.push(createSingleChoiceScale(T("survey.builder.single_choice_scale", "Tek seçimli ölçek"), rs(), 1, 10, true));
        } else if (which === "blank") {
            return;
        }
        toAppend.forEach((html) => $questions.append(html));
        initTinymce();
        window.__SURMEY_SURVEY_QUESTIONS = existing;
        generate();
        scrollToQuestion($questions.find("#question").last());
        setupBuilderSectionGroups();
        refreshPreviewIfVisible();
    }
    window.applySurveyTemplate = applyTemplate;

    function createSingleChoiceScale(title, slug, min = 1, max = 5, isRequired = false) {
        return createRadioWithOptions(title, createRangeOptionList(min, max), slug, isRequired);
    }

    $(function () {
        if (!window.__SURMEY_SURVEY_CREATE) return;
        try {
            const raw = localStorage.getItem(DRAFT_KEY);
            if (!raw) return;
            if ($("input[name=title]").val()) return;
            const d = JSON.parse(raw);
            if (!d.questions || !d.questions.length) return;
            const formStr = JSON.stringify(Object.assign({ title: "", about: "", verifyPhone: false, anonymous: false, photo: "" }, d.form || {}));
            prepareSurveyForEditing(formStr, JSON.stringify(d.questions));
            setupBuilderSectionGroups();
            const banner = $(`<div class="fixed top-3 inset-x-0 mx-auto max-w-lg z-50 rounded-md bg-blue-600 text-white text-sm px-4 py-2 shadow-lg text-center">${T("survey.builder.draft_banner")}</div>`);
            $("body").append(banner);
            setTimeout(() => banner.fadeOut(400, () => banner.remove()), 4500);
        } catch (e) { }
    });

    setTimeout(setupBuilderSectionGroups, 50);

    $("body").on("submit", "form[action*='/surveys/apply']", function () {
        try {
            localStorage.removeItem(DRAFT_KEY);
        } catch (e) { }
    });

    $("[data-json]").on("click", function () {

        const jsonData = $(this).data("json");

        const questionTitle = $(this).parent().parent().parent().prev().text();
        $("#answers-modal-title").text(questionTitle)

        $("#answersModalContent").html("")

        jsonData.forEach(element => {
            $("#answersModalContent").prepend('<div class="dark:bg-gray-700 bg-gray-50 my-2 text-sm text-gray-900 dark:text-gray-200 p-3 rounded-md">' + element.value + '</div>');
        });
    })

    window.previousPathname = window.location.pathname;

    setInterval(() => {
        window.HSStaticMethods.autoInit();

        const currentPathname = window.location.pathname;
        if (currentPathname !== previousPathname) {
            previousPathname = currentPathname;
            window.HSStaticMethods.autoInit();
        }
    }, 250);

    $(document).on("input change", ".preview-content input.js-scale-range", function () {
        $(this).closest(".preview-field").find(".js-scale-readout").text($(this).val());
    });
});
