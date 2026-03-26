document.addEventListener('DOMContentLoaded', () => {
	const modal = document.getElementById('icey_language_modal');
	const backdrop = document.getElementById('icey_language_modal_backdrop');
	const btn_cancel = document.getElementById('icey_stay_swedish');
	const btn_proceed = document.getElementById('icey_proceed_translate');
	const language_select = document.getElementById('icey_language_select');
	
	const session_key = 'icey_preferred_language';
	const warning_accepted_key = 'icey_gt_warning_accepted';
	const gt_cookie_name = 'googtrans';
    
    // Använd inställt standardspråk från PHP (oftast 'sv')
    const default_lang = (typeof iceyGTVars !== 'undefined') ? iceyGTVars.defaultLang : 'sv';

	// Hitta alla länkar som ska trigga modalen (klassen kan sättas på menyval etc)
	const language_links = document.querySelectorAll('.icey_language_toggle a, a.icey_language_toggle');

	if (!modal || !backdrop || !language_select) return;

	function deleteGTCookieAggressive() {
		const host = window.location.hostname;
		const domainParts = host.split('.');
		const domainsToClean = [host, `.${host}`];

		if (domainParts.length > 2) {
			const parentDomain = domainParts.slice(-2).join('.');
			domainsToClean.push(parentDomain, `.${parentDomain}`);
		}

		domainsToClean.forEach(d => {
			document.cookie = `${gt_cookie_name}=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=${d};SameSite=Lax`;
		});

		document.cookie = `${gt_cookie_name}=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;SameSite=Lax`;
	}

	function setGTCookie(value) {
		deleteGTCookieAggressive();
		const host = window.location.hostname;
		const domainParts = host.split('.');
		const targetDomain = (domainParts.length > 2) ? `.${domainParts.slice(-2).join('.')}` : `.${host}`;

		const expires = new Date();
		expires.setTime(expires.getTime() + (30 * 24 * 60 * 60 * 1000));
		document.cookie = `${gt_cookie_name}=${value};expires=${expires.toUTCString()};path=/;domain=${targetDomain};SameSite=Lax`;
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
			setGTCookie(`/${default_lang}/${default_lang}`);
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

	window.googleTranslateElementInit = function () {
        // Hämta aktiva språk från select-listans options
        const active_langs_array = Array.from(language_select.options).map(opt => opt.value);
        const active_langs_string = active_langs_array.join(',');

		new google.translate.TranslateElement({
			pageLanguage: default_lang,
			includedLanguages: active_langs_string,
			layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
			autoDisplay: false
		}, 'google_translate_element');
	};

	if (current_lang !== default_lang) {
		const gtScript = document.createElement('script');
		gtScript.type = 'text/javascript';
		gtScript.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
		document.body.appendChild(gtScript);
	}
});