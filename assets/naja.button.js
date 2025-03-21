let reqCnt = 0;
export default class SubmitButtonDisable {
	initialize(naja) {
		let submitButton = null;

		// Function to set the submitButton based on the clicked button
		const submitDisable = (doc) => {
			const submit = doc.querySelectorAll('[data-btn-submit]');
			if (submit) {
				submit.forEach(function (button) {
					button.addEventListener('click', () => submitButton = button);
				});
			}
		};

		// Initialize for the original document and after every snippet update
		submitDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => submitDisable(e.detail.snippet));

		// Disable the button before submission
		naja.addEventListener('start', (e) => {
			if (submitButton) {
				if (reqCnt === 0) {
					submitButton.disabled = true;
				}
				reqCnt++;
			}
		});

		// Re-enable the button after the request completes
		naja.addEventListener('complete', () => {
			if (submitButton) {
				if (--reqCnt === 0) {
					submitButton.disabled = false;
				}
			}
		});
	}
}
