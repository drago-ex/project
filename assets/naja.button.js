export default class SubmitButtonDisable {
	initialize(naja) {
		const submitDisable = (doc) => {
			doc.querySelectorAll('form').forEach(form => {
				if (form._submitListener) {
					form.removeEventListener('submit', form._submitListener);
				}

				const listener = (event) => {
					if (form.checkValidity()) {
						form.querySelectorAll('[data-btn-submit]').forEach(btn => btn.disabled = true);
					} else {
						event.preventDefault();
					}
				};

				form.addEventListener('submit', listener);
				form._submitListener = listener;
			});
		};

		submitDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			submitDisable(e.detail.snippet);
		});
	}
}
