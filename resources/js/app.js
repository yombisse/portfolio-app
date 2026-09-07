

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const themeStorageKey = 'portfolio-theme';

const updateThemeControls = () => {
	const isLightTheme = document.documentElement.classList.contains('theme-light');

	document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
		toggle.setAttribute('aria-label', isLightTheme ? 'Activer le thème sombre' : 'Activer le thème clair');
		toggle.setAttribute('title', isLightTheme ? 'Activer le thème sombre' : 'Activer le thème clair');
	});

	document.querySelectorAll('[data-theme-label]').forEach((label) => {
		label.textContent = isLightTheme ? 'Activer le thème sombre' : 'Activer le thème clair';
	});
};

document.addEventListener('click', (event) => {
	if (!event.target.closest('[data-theme-toggle]')) {
		return;
	}

	const isLightTheme = document.documentElement.classList.toggle('theme-light');
	localStorage.setItem(themeStorageKey, isLightTheme ? 'light' : 'dark');
	updateThemeControls();
});

updateThemeControls();

document.addEventListener('click', (event) => {
	const dateInput = event.target.closest('.date-picker-only');

	if (dateInput && typeof dateInput.showPicker === 'function') {
		dateInput.showPicker();
	}
});

document.addEventListener('keydown', (event) => {
	if (event.target.matches('.date-picker-only')) {
		event.preventDefault();
	}
});

document.addEventListener('paste', (event) => {
	if (event.target.matches('.date-picker-only')) {
		event.preventDefault();
	}
});

document.addEventListener('click', (event) => {
	if (event.target.closest('.floating-action-top')) {
		window.scrollTo({ top: 0, behavior: 'smooth' });
	}
});

Alpine.start();
