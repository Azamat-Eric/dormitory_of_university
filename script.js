const translateables = document.querySelectorAll(".translateable");
const translateables_copy = Array.from(translateables).map(element => element.cloneNode(true));
const langs = document.querySelectorAll(".lang");
const languageSelect = document.getElementById("language");

let siteLanguage = navigator.language || "ru";

// Привязываем обработчик события к элементам с классом .lang
langs.forEach(lang => {
    lang.addEventListener("click", () => language_changed(lang.id));
});

async function language_changed(lang) {
    for (let i = 0; i < translateables.length; i++) {
        if (lang != siteLanguage) {
            localStorage.setItem("site_lang", lang);
            translateables[i].innerHTML = await translateGoogle(siteLanguage, lang, translateables_copy[i].textContent);
        } else {
            localStorage.setItem("site_lang", siteLanguage);
            translateables[i].innerHTML = translateables_copy[i].innerHTML;
        }
    }

    // Изменение класса "changed_language" у языков
    langs.forEach(one_lang => {
        if (one_lang.id === lang) {
            one_lang.classList.add("changed_language");
        } else {
            one_lang.classList.remove("changed_language");
        }
    });
}

// Пример с переводчиком (оставляю как у вас)
async function translateGoogle(sourceLang, targetLang, text) {
    let googleURL = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${sourceLang}&tl=${targetLang}&dt=t&q=${encodeURI(text.replaceAll("\n", ''))}`;
    let newText = [];
    await fetch(googleURL).then(res => res.json()).then(data => {
        if (data[0] && Array.isArray(data[0])) {
            data[0].forEach(datas => {
                if (datas[0]) {
                    newText.push(datas[0]);
                }
            });
        }
    });
    return newText.join(" ").replaceAll(". ,", ". ");
}

// Проверяем язык при загрузке страницы
document.addEventListener("DOMContentLoaded", () => {
    const lang = localStorage.getItem("site_lang") || siteLanguage;
    language_changed(lang);
});


// let rooms = document.querySelectorAll(".rooms");
// sessionStorage.setItem("rooms", rooms.length);
// let inputs = document.querySelectorAll("input");
// inputs.forEach(input => {
//     input.addEventListener("input", function () {
//         let text = input.value;
//         let lang = localStorage.getItem("site_lang");
//         if (lang) {
//             translateGoogle("auto", lang, text).then(result => {
//                 input.value = result;
//             })
//         }
//     });
// })