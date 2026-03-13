export default class HyperlinkDisable {
	initialize(naja) {
		// Use WeakSet to track elements with attached listeners
		const listenerAttached = new WeakSet();
		const allTrackedLinks = [];

		const hyperlinkDisable = (doc) => {
			const links = doc.querySelectorAll('[data-link-disable]');
			links.forEach((link) => {
				// Prevent duplicate event listeners using WeakSet
				if (!listenerAttached.has(link)) {
					link.addEventListener('click', (event) => {
						link.classList.add('disabled');
					});
					listenerAttached.add(link);
					allTrackedLinks.push(link);
				}
			});
			return links;
		};

		hyperlinkDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			hyperlinkDisable(e.detail.snippet);
		});

		naja.addEventListener('complete', () => {
			// Re-enable only tracked links that are still in the DOM
			allTrackedLinks.forEach((link) => {
				if (document.contains(link)) {
					link.classList.remove('disabled');
				}
			});
		});
	}
}
