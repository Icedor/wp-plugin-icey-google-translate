document.addEventListener('DOMContentLoaded', () => {
	const modal = document.getElementById('icey_language_modal');
	const backdrop = document.getElementById('icey_language_modal_backdrop');
	const btn_cancel = document.getElementById('icey_cancel_translate');
	const btn_proceed = document.getElementById('icey_proceed_translate');
	const language_select = document.getElementById('icey_language_select');

	const session_key = 'icey_preferred_language';
	const warning_accepted_key = 'icey_gt_warning_accepted';
	const gt_cookie_name = 'googtrans';

	const default_lang = (typeof iceyGTVars !== 'undefined') ? iceyGTVars.defaultLang : 'sv';

	const language_links = document.querySelectorAll('.icey_language_toggle a, a.icey_language_toggle');

	if (!modal || !backdrop || !language_select) return;

	function deleteGTCookieAggressive() {
        const host = window.location.hostname;
        const parts = host.split('.');
        
        document.cookie = `${gt_cookie_name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
        document.cookie = `${gt_cookie_name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=${host};`;
        document.cookie = `${gt_cookie_name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.${host};`;

        if (parts.length > 1) {
            const rootDomain = parts.slice(-2).join('.');
            document.cookie = `${gt_cookie_name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=${rootDomain};`;
            document.cookie = `${gt_cookie_name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.${rootDomain};`;
        }
    }

    function setGTCookie(value) {
        deleteGTCookieAggressive();
        const expires = new Date();
        expires.setTime(expires.getTime() + (30 * 24 * 60 * 60 * 1000));
        document.cookie = `${gt_cookie_name}=${value}; expires=${expires.toUTCString()}; path=/; SameSite=Lax`;
    }

	function openModal() {
		modal.style.display = 'block';
		backdrop.style.display = 'block';
		setTimeout(() => {
			modal.classList.add('icey_modal_visible');
			backdrop.classList.add('icey_modal_visible');
		}, 20);
	}

	function closeModal() {
		modal.classList.remove('icey_modal_visible');
		backdrop.classList.remove('icey_modal_visible');
		setTimeout(() => {
			modal.style.display = 'none';
			backdrop.style.display = 'none';
		}, 400);
	}

	function executeTranslation(targetLang) {
        if (targetLang === default_lang) {
            deleteGTCookieAggressive();
        } else {
            setGTCookie(`/${default_lang}/${targetLang}`);
        }
        window.location.reload();
    }

	const current_lang = sessionStorage.getItem(session_key) || default_lang;
	language_select.value = current_lang;

	language_links.forEach(link => {
		link.addEventListener('click', (e) => {
			e.preventDefault();
			openModal();
		});
	});

	if (btn_cancel) {
		btn_cancel.addEventListener('click', closeModal);
	}

	if (btn_proceed) {
		btn_proceed.addEventListener('click', () => {
			const selected_lang = language_select.value;
			if (selected_lang === default_lang) {
				sessionStorage.setItem(session_key, default_lang);
				closeModal();
				executeTranslation(default_lang);
			} else {
				sessionStorage.setItem(warning_accepted_key, 'true');
				sessionStorage.setItem(session_key, selected_lang);
				closeModal();
				executeTranslation(selected_lang);
			}
		});
	}

	if (backdrop) {
		backdrop.addEventListener('click', closeModal);
	}

	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && modal.classList.contains('icey_modal_visible')) {
			closeModal();
		}
	});

	window['googleTranslateElementInit'] = function () {
        if (current_lang === default_lang) {
            return;
        }

        const active_langs_string = (typeof iceyGTVars !== 'undefined' && iceyGTVars.activeLangs) ? iceyGTVars.activeLangs : '';

        new google.translate.TranslateElement({
            pageLanguage: default_lang,
            includedLanguages: active_langs_string,
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    };

	if (current_lang !== default_lang || document.cookie.indexOf(gt_cookie_name + '=') !== -1) {
        const gtScript = document.createElement('script');
        gtScript.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
        document.body.appendChild(gtScript);
    }
});