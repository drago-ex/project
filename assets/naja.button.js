export default class SubmitButtonDisable {
	initialize(naja) {
		let submitButton = null;

		const submitDisable = (doc) => {
			doc.querySelectorAll('form').forEach(form => {
				const submits = form.querySelectorAll('[data-btn-submit]');
				submits.forEach(submit => {
					submit.addEventListener('click', () => {
						if (form && form.checkValidity()) {
							submitButton = submit;
						}
					});
				});
			});
		};

		submitDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			submitDisable(e.detail.snippet);
		});

		naja.addEventListener('start', () => {
			if (submitButton) {
				submitButton.disabled = true;
			}
		});

		naja.addEventListener('complete', () => {
			if (submitButton) {
				submitButton.disabled = false;
			}
		});
	}
}
