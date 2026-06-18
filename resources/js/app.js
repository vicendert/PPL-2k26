const toggles = document.querySelectorAll('[data-toggle-password]');

toggles.forEach((toggle) => {
	toggle.addEventListener('click', () => {
		const input = toggle.parentElement?.querySelector('[data-password]');

		if (!input) {
			return;
		}

		const isPassword = input.getAttribute('type') === 'password';
		input.setAttribute('type', isPassword ? 'text' : 'password');
		toggle.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
		toggle.textContent = isPassword ? 'Sembunyikan' : 'Lihat';
	});
});
