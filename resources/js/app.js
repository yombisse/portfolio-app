

import Alpine from 'alpinejs';

window.Alpine = Alpine;

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
