export default class HyperlinkDisable {
	initialize(naja) {
		const hyperlinkDisable = (doc) => {
			const links = doc.querySelectorAll('a.btn.ajax');
			links.forEach((link) => {
				link.addEventListener('click', (event) => {
					event.preventDefault();
					link.classList.add('disabled');
				});
			});
		};

		hyperlinkDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => hyperlinkDisable(e.detail.snippet));
		naja.addEventListener('complete', () => {
			const disabledLinks = document.querySelectorAll('a.btn.disabled');
			disabledLinks.forEach((link) => {
				link.classList.remove('disabled');
			});
		});
	}
}
