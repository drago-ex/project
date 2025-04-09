let reqCnt = 0;

export default class PasswordToggle {
	initialize(naja) {
		const togglePassword = (input, span) => {
			input.type = input.type === 'password' ? 'text' : 'password';
			span.textContent = span.textContent === 'Show' ? 'Hide' : 'Show';
		};

		const addPasswordToggle = (doc) => {
			doc.querySelectorAll('.password-toggle').forEach((element) => {
				let span = element.querySelector('.show-hide-btn');
				let input = element.querySelector('input');

				if (!span) {
					span = document.createElement('span');
					span.className = 'show-hide-btn';
					span.textContent = 'Show';
					element.appendChild(span);
				}

				if (!span._isListenerAdded) {
					span.addEventListener('click', () => togglePassword(input, span));
					span._isListenerAdded = true;
				}
			});
		};

		addPasswordToggle(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			addPasswordToggle(e.detail.snippet);
		});

		naja.addEventListener('complete', () => {
			if (--reqCnt === 0) {
				document.querySelectorAll('.password-toggle').forEach((element) => {
					const span = element.querySelector('.show-hide-btn');
					const input = element.querySelector('input');
					if (span.textContent === 'Hide') {
						input.type = 'password';
						span.textContent = 'Show';
					}
				});
			}
		});
	}
}
