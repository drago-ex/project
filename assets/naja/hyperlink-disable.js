export default class HyperlinkDisable {
	initialize(naja) {
		const hyperlinkDisable = (doc) => {
			const links = doc.querySelectorAll('[data-link-disable]');
			links.forEach((link) => {
				link.addEventListener('click', (event) => {
					link.classList.add('disabled');
				});
			});
			return links;
		};

		hyperlinkDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			hyperlinkDisable(e.detail.snippet);
		});

		// All of them, also links that came in a snippet after the page was loaded.
		naja.addEventListener('complete', () => {
			document.querySelectorAll('[data-link-disable].disabled').forEach((link) => {
				link.classList.remove('disabled');
			});
		});
	}
}
