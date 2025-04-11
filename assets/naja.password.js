export default class PasswordToggle {
	initialize(naja) {
		const getPasswordElements = (element) => {
			const input = element.querySelector('.input-password');
			const button = element.querySelector('.toggle-password');
			return { input, button };
		};

		const togglePassword = (input) => {
			input.type = input.type === 'password' ? 'text' : 'password';
		};

		const toggleButtonVisibility = (button, isVisible) => {
			if (isVisible) {
				button.classList.remove('d-none');
			} else {
				button.classList.add('d-none');
			}
		};

		const addPasswordToggle = (doc) => {
			doc.querySelectorAll('.input-group').forEach((element) => {
				const { input, button } = getPasswordElements(element);
				if (input) {
					const isPasswordVisible = input.type === 'password';
					toggleButtonVisibility(button, isPasswordVisible);  // Toggle button visibility based on input type

					if (button && !button._isListenerAdded) {
						button.addEventListener('click', () => togglePassword(input));
						button._isListenerAdded = true;
					}
				}
			});
		};

		addPasswordToggle(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			addPasswordToggle(e.detail.snippet);
		});

		naja.addEventListener('complete', () => {
			document.querySelectorAll('.input-group').forEach((element) => {
				const { input, button } = getPasswordElements(element);
				if (input && button) {
					const isPasswordVisible = input.type === 'password';
					toggleButtonVisibility(button, isPasswordVisible);
				}
			});
		});
	}
}
