export default class HyperlinkDisable {
	initialize(naja) {
		// Use WeakSet to track elements with attached listeners
		const listenerAttached = new WeakSet();

		const hyperlinkDisable = (doc) => {
			const links = doc.querySelectorAll('[data-link-disable]');
			links.forEach((link) => {
				// Prevent duplicate event listeners using WeakSet
				if (!listenerAttached.has(link)) {
					link.addEventListener('click', (event) => {
						link.classList.add('disabled');
					});
					listenerAttached.add(link);
				}
			});
			return links;
		};

		const initialLinks = hyperlinkDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			hyperlinkDisable(e.detail.snippet);
		});

		naja.addEventListener('complete', () => {
			initialLinks.forEach((link) => {
				link.classList.remove('disabled');
			});
		});
	}
}
