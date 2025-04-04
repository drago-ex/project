let reqCnt = 0;
export default class HyperlinkDisable {
	initialize(naja) {
		const hyperlinkDisable = (doc) => {
			const links = doc.querySelectorAll('[data-link-disable]');
			links.forEach((link) => {
				link.addEventListener('click', (event) => {
					link.classList.add('disabled');
					reqCnt++;
				});
			});
			return links;
		};

		const initialLinks = hyperlinkDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			hyperlinkDisable(e.detail.snippet);
		});

		naja.addEventListener('complete', () => {
			if (--reqCnt === 0) {
				initialLinks.forEach((link) => {
					link.classList.remove('disabled');
				});
			}
		});
	}
}
