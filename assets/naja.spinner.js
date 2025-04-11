export default class SpinnerExtension {
	/**
	 * Initializes the spinner extension for Naja requests.
	 * @param {Object} naja Naja instance for handling events.
	 */
	initialize(naja) {
		// Prevent adding the spinner multiple times
		if (document.querySelector('.spinner')) return;

		// Create a spinner element and add a class for styling.
		const el = document.createElement('div');
		el.classList.add('spinner');
		document.body.appendChild(el);

		// Initially hide the spinner until it's needed.
		el.style.display = 'none';

		// Show the spinner when a request starts (first request).
		naja.addEventListener('start', () => {
			el.style.display = 'block';
		});

		// Hide the spinner after the last request completes.
		naja.addEventListener('complete', () => {
			el.style.display = 'none';
		});
	}
}
