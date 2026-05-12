export default class DataGridPage {
	initialize(naja) {
		const dataGridPage = (doc) => {
			const itemCount = doc.querySelectorAll('[data-items-page]');
			if (itemCount) {
				for(let item of itemCount) {
					item.addEventListener('change', (e) => {
						const select = e.target;
						const template = select.getAttribute('data-url-template');
						if (template) {
							const url = template.replace('__SIZE__', select.value);
							naja.makeRequest('GET', url, null, {history: 'replace'}).then();
						}
					});
				}
			}
		}

		dataGridPage(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => dataGridPage(e.detail.snippet));
	}
}
