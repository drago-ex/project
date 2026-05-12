export default class DataGridFilter {
	initialize(naja) {
		const applyFilters = (doc) => {
			const inputs = doc.querySelectorAll('[data-items-filter]');
			if (!inputs) return;

			for (let input of inputs) {
				input.addEventListener('keydown', (e) => {
					if (e.key === 'Enter') {
						e.preventDefault();
						naja.uiHandler.submitForm(e.target.form);
					}
				});
			}
		};

		applyFilters(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => applyFilters(e.detail.snippet));
	}
}
