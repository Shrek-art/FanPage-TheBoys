document.addEventListener("DOMContentLoaded", () => {
    const characters = {
        butcher: {
            name: "Billy Butcher",
            role: "Leader — The Boys",
            img: "images/7f691723a0c31fef2a322cd6b78e5484-removebg-preview.png",
            desc: "Liderul neînfricat al grupului The Boys. Urăște supereroii cu o pasiune personală și este dispus să facă orice pentru a-i opri pe Vought și pe cei ca ei.",
            video: "clips/butcher.mp4"
        },

        hughie: {
            name: "Hughie Campbell",
            role: "Membru — The Boys",
            img: "images/50e05182f1d278d9bdb717fe8cf6fd45-removebg-preview.png",
            desc: "Un tip obișnuit tras în lupta împotriva supereroilor după o tragedie personală. Este inima morală a grupului.",
            video: "clips/campbell.mp4"
        },

        milk: {
            name: "Mother's Milk",
            role: "Membru — The Boys",
            img: "images/34f158721f8b44105f0054ddf4117c52-removebg-preview.png",
            desc: "Organizatul și conștiința grupului. Are o datorie personală față de misiune și încearcă să echilibreze familia cu lupta.",
            video: "clips/milky.mp4"
        },

        frenchie: {
            name: "Frenchie",
            role: "Specialist arme — The Boys",
            img: "images/787f3725c206b77390a2a560edb93116-removebg-preview.png",
            desc: "Expert în explozivi și substanțe chimice. Are un trecut misterios și o legătură profundă cu Kimiko.",
            video: "clips/frenchie.mp4"
        },

        kimiko: {
            name: "Kimiko",
            role: "Membru — The Boys",
            img: "images/ba6d90d2b0f30af07e4ecb192c417cce-removebg-preview.png",
            desc: "O super-umană forțată să devină armă. Nu vorbește, dar comunică prin acțiuni. Are o forță colosală și un suflet sensibil.",
            video: "clips/kimiko.mp4"
        },

        homelander: {
            name: "Homelander",
            role: "Leader — The Seven",
            img: "images/homelander.png",
            desc: "Cel mai puternic și periculos super-erou. În fața publicului pare un simbol al dreptății, dar în realitate este manipulator și instabil.",
            video: "clips/homelander.mp4"
        },

        starlight: {
            name: "Starlight",
            role: "Member — The Seven",
            img: "images/starlight.png",
            desc: "O super-eroină care încearcă să rămână corectă într-un sistem corupt. Ea descoperă treptat adevărata față a companiei Vought.",
            video: "clips/starlight.mp4"
        },

        soldierboy: {
            name: "Soldier Boy",
            role: "Former Hero",
            img: "images/soldierboy.png",
            desc: "Un fost erou legendar, puternic și violent, considerat unul dintre primii mari supereroi creați de Vought.",
            video: "clips/soldierboy.mp4"
        },

        atrain: {
            name: "A-Train",
            role: "Member — The Seven",
            img: "images/atrain.png",
            desc: "A-Train este un super-erou cunoscut pentru viteza sa extraordinară și pentru conflictele sale interioare.",
            video: "clips/atrain.mp4"
        },

        thedeep: {
            name: "The Deep",
            role: "Member — The Seven",
            img: "images/thedeep.png",
            desc: "The Deep este un super-erou cu abilități acvatice, dar deseori este prezentat ca fiind imatur și superficial.",
            video: "clips/thedeep.mp4"
        },

        blacknoir: {
            name: "Black Noir",
            role: "Member — The Seven",
            img: "images/blacknoir.png",
            desc: "Black Noir este un personaj misterios, tăcut și extrem de periculos în luptă.",
            video: "clips/blacknoir.mp4"
        },

        queenmaeve: {
            name: "Queen Maeve",
            role: "Member — The Seven",
            img: "images/queenmaeve.png",
            desc: "Queen Maeve este una dintre cele mai puternice super-eroine, dar este prinsă între imaginea publică și propriile convingeri.",
            video: "clips/queenmaeve.mp4"
        },

        translucent: {
            name: "Translucent",
            role: "Member — The Seven",
            img: "images/translucent.png",
            desc: "Translucent are abilitatea de a deveni invizibil, fiind unul dintre membrii originali ai echipei The Seven.",
            video: "clips/translucent.mp4"
        }
    };

    const homeContent = document.getElementById("homeContent");

    const homeNavItem = document.getElementById("homeNavItem");
    const homeNav = document.getElementById("homeNav");

    const exploreBtn = document.getElementById("exploreBtn");
    const characterMenu = document.getElementById("characterMenu");

    const showBoys = document.getElementById("showBoys");
    const showSupes = document.getElementById("showSupes");

    const boysGrid = document.getElementById("boysGrid");
    const supesGrid = document.getElementById("supesGrid");

    const charactersTitle = document.getElementById("charactersTitle");
    const scrollBtn = document.getElementById("scrollBtn");

    const charactersNav = document.getElementById("charactersNav");
    const charactersPage = document.getElementById("charactersPage");
    const backCharactersHomeBtn = document.getElementById("backCharactersHomeBtn");

    const episodesNav = document.getElementById("episodesNav");
    const episodesPage = document.getElementById("episodesPage");
    const backHomeBtn = document.getElementById("backHomeBtn");

    const loreNav = document.getElementById("loreNav");
    const loreSection = document.getElementById("loreSection");

    const searchInput = document.getElementById("searchInput");
    const searchBtn = document.getElementById("searchBtn");

    const profileBtn = document.getElementById("profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");

    const overlay = document.getElementById("modalOverlay");
    const modalClose = document.getElementById("modalClose");
    const modalVideo = document.getElementById("modalVideo");

    const loginPrompt = document.getElementById("loginPrompt");
    const loginPromptClose = document.getElementById("loginPromptClose");

    function showHomePage() {
        if (homeContent) {
            homeContent.style.display = "block";
        }

        if (charactersPage) {
            charactersPage.classList.remove("active");
        }

        if (episodesPage) {
            episodesPage.classList.remove("active");
        }

        if (homeNavItem) {
            homeNavItem.classList.remove("show");
        }

        if (searchInput) {
            searchInput.value = "";
        }

        showAllCharacterCards();

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    function showHomeButton() {
        if (homeNavItem) {
            homeNavItem.classList.add("show");
        }
    }

    function showAllCharacterCards() {
        document.querySelectorAll(".characters-page .character-card").forEach(card => {
            card.style.display = "block";
        });
    }

    function openCharactersPage() {
        if (homeContent) {
            homeContent.style.display = "none";
        }

        if (episodesPage) {
            episodesPage.classList.remove("active");
        }

        if (charactersPage) {
            charactersPage.classList.add("active");
        }

        showHomeButton();

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    function openEpisodesPage() {
        if (homeContent) {
            homeContent.style.display = "none";
        }

        if (charactersPage) {
            charactersPage.classList.remove("active");
        }

        if (episodesPage) {
            episodesPage.classList.add("active");
        }

        showHomeButton();

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    function searchCharacters() {
        if (!searchInput) {
            return;
        }

        const searchValue = searchInput.value.trim().toLowerCase();

        if (searchValue === "") {
            return;
        }

        openCharactersPage();

        let found = false;

        document.querySelectorAll(".characters-page .character-card").forEach(card => {
            const characterId = card.dataset.id;
            const characterData = characters[characterId];

            if (!characterData) {
                card.style.display = "none";
                return;
            }

            const name = characterData.name.toLowerCase();
            const role = characterData.role.toLowerCase();

            if (
                name.includes(searchValue) ||
                role.includes(searchValue) ||
                characterId.includes(searchValue)
            ) {
                card.style.display = "block";
                found = true;
            } else {
                card.style.display = "none";
            }
        });

        if (!found) {
            alert("No character found.");
            showAllCharacterCards();
        }
    }

    /* ===== STARE INIȚIALĂ ===== */

    if (homeContent) {
        homeContent.style.display = "block";
    }

    if (boysGrid) {
        boysGrid.style.display = "grid";
    }

    if (supesGrid) {
        supesGrid.style.display = "none";
    }

    if (characterMenu) {
        characterMenu.setAttribute("hidden", "");
    }

    if (showBoys && showSupes) {
        showBoys.classList.add("active-team");
        showSupes.classList.remove("active-team");
    }

    if (charactersPage) {
        charactersPage.classList.remove("active");
    }

    if (episodesPage) {
        episodesPage.classList.remove("active");
    }

    if (homeNavItem) {
        homeNavItem.classList.remove("show");
    }

    /* ===== PROFILE DROPDOWN ===== */

    if (profileBtn && profileDropdown) {
    document.body.appendChild(profileDropdown);

    profileBtn.addEventListener("click", event => {
        event.preventDefault();
        event.stopPropagation();

        const rect = profileBtn.getBoundingClientRect();

        profileDropdown.style.position = "fixed";
        profileDropdown.style.top = rect.bottom + 12 + "px";
        profileDropdown.style.right = window.innerWidth - rect.right + "px";

        profileDropdown.classList.toggle("active");
    });

    profileDropdown.addEventListener("click", event => {
        event.stopPropagation();
    });

    document.addEventListener("click", () => {
        profileDropdown.classList.remove("active");
    });

    window.addEventListener("resize", () => {
        profileDropdown.classList.remove("active");
    });
}

    /* ===== HOME DIN NAVBAR ===== */

    if (homeNav) {
        homeNav.addEventListener("click", event => {
            event.preventDefault();
            showHomePage();
        });
    }

    /* ===== EXPLORE CHARACTERS ===== */

    if (exploreBtn && characterMenu) {
        exploreBtn.addEventListener("click", () => {
            if (characterMenu.hasAttribute("hidden")) {
                characterMenu.removeAttribute("hidden");
                exploreBtn.dataset.i18n = "close";
                exploreBtn.textContent = (translations?.[localStorage.getItem("language") || "en"]?.close) || "Close ✕";
            } else {
                characterMenu.setAttribute("hidden", "");
                exploreBtn.dataset.i18n = "explore_characters";
                exploreBtn.textContent = (translations?.[localStorage.getItem("language") || "en"]?.explore_characters) || "Explore Characters";
            }
        });
    }

    /* ===== THE BOYS DIN EXPLORE ===== */

    if (showBoys && boysGrid && supesGrid && charactersTitle && characterMenu && exploreBtn && showSupes) {
        showBoys.addEventListener("click", () => {
            boysGrid.style.display = "grid";
            supesGrid.style.display = "none";

            charactersTitle.dataset.i18n = "boys_characters";
            charactersTitle.textContent = (translations?.[localStorage.getItem("language") || "en"]?.boys_characters) || "The Boys Characters";

            showBoys.classList.add("active-team");
            showSupes.classList.remove("active-team");

            characterMenu.setAttribute("hidden", "");
            exploreBtn.dataset.i18n = "explore_characters";
                exploreBtn.textContent = (translations?.[localStorage.getItem("language") || "en"]?.explore_characters) || "Explore Characters";

            charactersTitle.scrollIntoView({
                behavior: "smooth"
            });
        });
    }

    /* ===== THE SUPES DIN EXPLORE ===== */

    if (showSupes && boysGrid && supesGrid && charactersTitle && characterMenu && exploreBtn && showBoys) {
        showSupes.addEventListener("click", () => {
            boysGrid.style.display = "none";
            supesGrid.style.display = "grid";

            charactersTitle.dataset.i18n = "supes_characters";
            charactersTitle.textContent = (translations?.[localStorage.getItem("language") || "en"]?.supes_characters) || "The Supes Characters";

            showSupes.classList.add("active-team");
            showBoys.classList.remove("active-team");

            characterMenu.setAttribute("hidden", "");
            exploreBtn.dataset.i18n = "explore_characters";
                exploreBtn.textContent = (translations?.[localStorage.getItem("language") || "en"]?.explore_characters) || "Explore Characters";

            charactersTitle.scrollIntoView({
                behavior: "smooth"
            });
        });
    }

    /* ===== SCROLL DOWN ===== */

    if (scrollBtn && charactersTitle) {
        scrollBtn.addEventListener("click", () => {
            charactersTitle.scrollIntoView({
                behavior: "smooth"
            });
        });
    }

    /* ===== CHARACTERS DIN NAVBAR ===== */

    if (charactersNav && charactersPage && homeContent) {
        charactersNav.addEventListener("click", event => {
            event.preventDefault();

            if (searchInput) {
                searchInput.value = "";
            }

            showAllCharacterCards();
            openCharactersPage();
        });
    }

    if (backCharactersHomeBtn) {
        backCharactersHomeBtn.addEventListener("click", () => {
            showHomePage();
        });
    }

    /* ===== EPISODES DIN NAVBAR ===== */

    if (episodesNav && episodesPage && homeContent) {
        episodesNav.addEventListener("click", event => {
            event.preventDefault();
            openEpisodesPage();
        });
    }

    if (backHomeBtn) {
        backHomeBtn.addEventListener("click", () => {
            showHomePage();
        });
    }

    /* ===== LORE DIN NAVBAR ===== */

    if (loreNav && loreSection && homeContent) {
        loreNav.addEventListener("click", event => {
            event.preventDefault();

            if (homeContent) {
                homeContent.style.display = "block";
            }

            if (charactersPage) {
                charactersPage.classList.remove("active");
            }

            if (episodesPage) {
                episodesPage.classList.remove("active");
            }

            if (homeNavItem) {
                homeNavItem.classList.remove("show");
            }

            loreSection.scrollIntoView({
                behavior: "smooth"
            });
        });
    }

    /* ===== SEARCH BAR ===== */

    if (searchBtn && searchInput) {
        searchBtn.addEventListener("click", () => {
            searchCharacters();
        });

        searchInput.addEventListener("keydown", event => {
            if (event.key === "Enter") {
                event.preventDefault();
                searchCharacters();
            }
        });
    }

    /* ===== CLICK PE CHARACTER CARD ===== */

    document.querySelectorAll(".character-card").forEach(card => {
        card.addEventListener("click", () => {
            if (!isLoggedIn) {
                if (loginPrompt) {
                    loginPrompt.classList.add("active");
                }
                return;
            }

            const data = characters[card.dataset.id];

            if (!data) {
                return;
            }

            const modalName = document.getElementById("modalName");
            const modalRole = document.getElementById("modalRole");
            const modalImg = document.getElementById("modalImg");
            const modalDesc = document.getElementById("modalDesc");

            if (modalName) {
                modalName.textContent = data.name;
            }

            if (modalRole) {
                modalRole.textContent = data.role;
            }

            if (modalImg) {
                modalImg.src = data.img;
            }

            if (modalDesc) {
                modalDesc.textContent = data.desc;
            }

            if (modalVideo) {
                modalVideo.src = data.video;
            }

            if (overlay) {
                overlay.classList.add("active");
            }
        });
    });

    /* ===== ÎNCHIDERE MODAL CHARACTER ===== */

    if (modalClose && overlay && modalVideo) {
        modalClose.addEventListener("click", () => {
            overlay.classList.remove("active");
            modalVideo.pause();
            modalVideo.currentTime = 0;
        });

        overlay.addEventListener("click", event => {
            if (event.target === overlay) {
                overlay.classList.remove("active");
                modalVideo.pause();
                modalVideo.currentTime = 0;
            }
        });
    }

    /* ===== ÎNCHIDERE LOGIN POPUP ===== */

    if (loginPromptClose && loginPrompt) {
        loginPromptClose.addEventListener("click", () => {
            loginPrompt.classList.remove("active");
        });

        loginPrompt.addEventListener("click", event => {
            if (event.target === loginPrompt) {
                loginPrompt.classList.remove("active");
            }
        });
    }


    /* ===== DARK / LIGHT MODE + LANGUAGE SWITCH ===== */

    const translations = {
        en: {
            theme_light: "Light mode", theme_dark: "Dark mode", search_characters: "Search characters...",
            nav_home: "Home", nav_contact: "Contact", nav_characters: "Characters", nav_episodes: "Episodes", nav_lore: "Lore", nav_auth: "Authentication", nav_logout: "Log Out",
            explore_characters: "Explore Characters", close: "Close ✕", scroll_down: "Scroll down ˅", boys_characters: "The Boys Characters", supes_characters: "The Supes Characters", actors: "Actors", lore_title: "Lore", characters_title: "Characters", episodes_title: "Episodes", back_home: "← Back to Home",
            must_login: "You have to be logged in", auth_to_see: "Authenticate to see info about characters.", login: "Log In", register: "Register", my_messages: "My Messages", guest: "Guest", not_authenticated: "Not authenticated", email_not_saved: "Email not saved", status_label: "Status:", logged_in: "Logged in", messages_sent: "Messages sent:", last_message: "Last message:",
            contact_title: "CONTACT", contact_subtitle: "You have a theory? Found an error? Contact us.", name: "Name", email: "Email", subject: "Subject", message: "Message", send_message: "Send message", choose_subject: "Choose a subject...", general_question: "General question", theory: "Theory / speculation", report_error: "Report an error", collaboration: "Collaboration", other: "Other", max_1000: "Max. 1000 characters",
            auth_title: "AUTHENTICATION", auth_subtitle: "Join the resistance or sign back in.", create_account: "Create Account", password: "Password", confirm_password: "Confirm Password", username: "Username",
            messages_title: "My Messages", messages_subtitle: "Here you can view, edit or delete the contact messages sent from your account.", no_messages: "You have not sent any messages yet.", save_changes: "Save changes", delete: "Delete", edit: "Edit"
        },
        ro: {
            theme_light: "Mod luminos", theme_dark: "Mod întunecat", search_characters: "Caută personaje...",
            nav_home: "Acasă", nav_contact: "Contact", nav_characters: "Personaje", nav_episodes: "Episoade", nav_lore: "Poveste", nav_auth: "Autentificare", nav_logout: "Ieșire",
            explore_characters: "Explorează personajele", close: "Închide ✕", scroll_down: "Derulează în jos ˅", boys_characters: "Personajele The Boys", supes_characters: "Personajele Supes", actors: "Actori", lore_title: "Poveste", characters_title: "Personaje", episodes_title: "Episoade", back_home: "← Înapoi acasă",
            must_login: "Trebuie să fii autentificat", auth_to_see: "Autentifică-te pentru a vedea informații despre personaje.", login: "Logare", register: "Înregistrare", my_messages: "Mesajele mele", guest: "Oaspete", not_authenticated: "Neautentificat", email_not_saved: "Email nesalvat", status_label: "Status:", logged_in: "Autentificat", messages_sent: "Mesaje trimise:", last_message: "Ultimul mesaj:",
            contact_title: "CONTACT", contact_subtitle: "Ai o teorie? Ai găsit o greșeală? Contactează-ne.", name: "Nume", email: "Email", subject: "Subiect", message: "Mesaj", send_message: "Trimite mesaj", choose_subject: "Alege un subiect...", general_question: "Întrebare generală", theory: "Teorie / speculație", report_error: "Raportează o eroare", collaboration: "Colaborare", other: "Altceva", max_1000: "Max. 1000 de caractere",
            auth_title: "AUTENTIFICARE", auth_subtitle: "Alătură-te rezistenței sau conectează-te din nou.", create_account: "Creează cont", password: "Parolă", confirm_password: "Confirmă parola", username: "Username",
            messages_title: "Mesajele mele", messages_subtitle: "Aici poți vedea, modifica sau șterge mesajele de contact trimise de pe contul tău.", no_messages: "Nu ai trimis încă niciun mesaj.", save_changes: "Salvează modificările", delete: "Șterge", edit: "Modifică"
        },
        ru: {
            theme_light: "Светлая тема", theme_dark: "Тёмная тема", search_characters: "Поиск персонажей...",
            nav_home: "Главная", nav_contact: "Контакты", nav_characters: "Персонажи", nav_episodes: "Эпизоды", nav_lore: "История", nav_auth: "Вход", nav_logout: "Выйти",
            explore_characters: "Посмотреть персонажей", close: "Закрыть ✕", scroll_down: "Вниз ˅", boys_characters: "Персонажи The Boys", supes_characters: "Персонажи Supes", actors: "Актёры", lore_title: "История", characters_title: "Персонажи", episodes_title: "Эпизоды", back_home: "← Назад на главную",
            must_login: "Нужно войти в аккаунт", auth_to_see: "Войдите, чтобы увидеть информацию о персонажах.", login: "Войти", register: "Регистрация", my_messages: "Мои сообщения", guest: "Гость", not_authenticated: "Не авторизован", email_not_saved: "Email не сохранён", status_label: "Статус:", logged_in: "Авторизован", messages_sent: "Отправлено сообщений:", last_message: "Последнее сообщение:",
            contact_title: "КОНТАКТЫ", contact_subtitle: "Есть теория? Нашли ошибку? Свяжитесь с нами.", name: "Имя", email: "Email", subject: "Тема", message: "Сообщение", send_message: "Отправить", choose_subject: "Выберите тему...", general_question: "Общий вопрос", theory: "Теория / предположение", report_error: "Сообщить об ошибке", collaboration: "Сотрудничество", other: "Другое", max_1000: "Макс. 1000 символов",
            auth_title: "АВТОРИЗАЦИЯ", auth_subtitle: "Присоединяйтесь к сопротивлению или войдите снова.", create_account: "Создать аккаунт", password: "Пароль", confirm_password: "Подтвердите пароль", username: "Username",
            messages_title: "Мои сообщения", messages_subtitle: "Здесь можно просматривать, изменять или удалять контактные сообщения, отправленные с вашего аккаунта.", no_messages: "Вы ещё не отправляли сообщений.", save_changes: "Сохранить", delete: "Удалить", edit: "Редактировать"
        }
    };

    function applyTheme(theme) {
        const html = document.documentElement;
        html.classList.remove("light-mode", "dark-mode");
        html.classList.add(theme === "light" ? "light-mode" : "dark-mode");
        localStorage.setItem("theme", theme);
        updateThemeButton();
    }

    function updateThemeButton() {
        const themeToggle = document.getElementById("themeToggle");
        if (!themeToggle) return;
        const lang = localStorage.getItem("language") || "en";
        const theme = localStorage.getItem("theme") || "dark";
        themeToggle.textContent = translations[lang][theme === "light" ? "theme_dark" : "theme_light"];
    }

    function applyLanguage(lang) {
        if (!translations[lang]) lang = "en";
        localStorage.setItem("language", lang);
        document.documentElement.lang = lang;

        document.querySelectorAll("[data-i18n]").forEach(element => {
            const key = element.dataset.i18n;
            if (translations[lang][key]) {
                element.textContent = translations[lang][key];
            }
        });

        document.querySelectorAll("[data-i18n-placeholder]").forEach(element => {
            const key = element.dataset.i18nPlaceholder;
            if (translations[lang][key]) {
                element.placeholder = translations[lang][key];
            }
        });

        const languageSelect = document.getElementById("languageSelect");
        if (languageSelect) languageSelect.value = lang;
        updateThemeButton();
    }

    const savedTheme = localStorage.getItem("theme") || "dark";
    applyTheme(savedTheme);
    applyLanguage(localStorage.getItem("language") || "en");

    const themeToggle = document.getElementById("themeToggle");
    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            const currentTheme = localStorage.getItem("theme") || "dark";
            applyTheme(currentTheme === "dark" ? "light" : "dark");
        });
    }

    const languageSelect = document.getElementById("languageSelect");
    if (languageSelect) {
        languageSelect.addEventListener("change", () => {
            applyLanguage(languageSelect.value);
        });
    }

});
