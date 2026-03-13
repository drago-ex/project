export default class SpinnerExtension {
	initialize(naja) {
		let activeRequests = 0;
		// Use specific selector to avoid conflicts with other spinners
		let el = document.querySelector('.naja-spinner');

		// Create spinner element only if it doesn't exist
		if (!el) {
			el = document.createElement('div');
			el.classList.add('naja-spinner', 'spinner');
			el.hidden = true;
			document.body.appendChild(el);
		}

		// Show the spinner when a request starts
		naja.addEventListener('start', () => {
			if (activeRequests === 0) {
				el.hidden = false;
			}
			activeRequests++;
		});

		// Hide the spinner when the last request completes
		naja.addEventListener('complete', () => {
			activeRequests--;
			if (activeRequests === 0) {
				el.hidden = true;
			}
		});
	}
}
