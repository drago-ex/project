export default class ErrorsExtension {
	/**
	 * Initializes the error handling extension for Naja requests.
	 * @param {Object} naja Naja instance for handling events.
	 */
	initialize(naja) {
		// Error messages corresponding to specific HTTP status codes.
		const errorMessages = {
			403: 'You do not have the necessary permissions to perform this action.',
			404: 'Page not found.',
			500: 'Internal server error, please try again later.',
			401: 'You are not authorized to perform this action.',
			// Add more error codes as needed
		};

		// Listen for error events from Naja.
		naja.addEventListener('error', (e) => {
			const error = e.detail.error;

			// Ensure the error response has a status
			if (!error.response || !error.response.status) {
				console.error('Error: Invalid response or missing status.');
				return;
			}

			// Retrieve the error message from the predefined error messages or fallback to the default message.
			const errorMessage = errorMessages[error.response.status] || 'An unexpected error occurred.';

			// Find the element where the error message will be displayed.
			const snippet = document.getElementById('snippet--message');
			if (!snippet) {
				console.error('Error: Snippet element with ID "snippet--message" not found.');
				return;
			}

			// Create new elements to display the error message.
			const div = document.createElement('div');
			div.className = 'alert alert-dismissible fade show border-0 rounded alert-danger';
			// z-index 1030 ensures alert appears above Bootstrap modals (z-index 1055) backdrop (1050)
			div.style.zIndex = '1030';
			div.textContent = errorMessage;

			// Create a button to close the alert.
			const button = document.createElement('button');
			button.className = 'btn-close';
			button.setAttribute('data-bs-dismiss', 'alert');
			div.append(button);

			// Batch DOM update with single operation to minimize reflows
			snippet.replaceChildren(div);
		});
	}
}
