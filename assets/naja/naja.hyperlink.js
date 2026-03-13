export default class HyperlinkDisable {
	initialize(naja) {
		const hyperlinkDisable = (doc) => {
			const links = doc.querySelectorAll('[data-link-disable]');
			links.forEach((link) => {
				// Prevent duplicate event listeners
				if (!link.hasAttribute('data-listener-attached')) {
					link.addEventListener('click', (event) => {
						link.classList.add('disabled');
					});
					link.setAttribute('data-listener-attached', 'true');
				}
			});
			return links;
		};

		const initialLinks = hyperlinkDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			hyperlinkDisable(e.detail.snippet);
		});

		naja.addEventListener('complete', () => {
			// Re-enable all links with the data-link-disable attribute
			document.querySelectorAll('[data-link-disable]').forEach((link) => {
				link.classList.remove('disabled');
			});
		});
	}
}
