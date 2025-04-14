export default class PasswordToggle {
	initialize(naja) {
		const attachPasswordToggle = (root) => {
			root.querySelectorAll('.input-group').forEach((group) => {
				const input = group.querySelector('.input-password');
				const button = group.querySelector('.toggle-password');

				if (input && button && !button._isListenerAdded) {
					button.addEventListener('click', () => {
						const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
						input.setAttribute('type', type);
					});
					button._isListenerAdded = true;
					button.classList.remove('d-none');
				}
			});
		};

		attachPasswordToggle(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			attachPasswordToggle(e.detail.snippet);
		});
	}
}
