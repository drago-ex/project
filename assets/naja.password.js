let reqCnt = 0;

export default class PasswordToggle {
	initialize(naja) {
		const toggleClass = (element, removeClass, addClass) => {
			element.classList.remove(removeClass);
			element.classList.add(addClass);
		};

		const togglePassword = (input, span) => {
			input.type = input.type === 'password' ? 'text' : 'password';
			if (span.classList.contains('hide')) {
				toggleClass(span, 'hide', 'show');
			} else {
				toggleClass(span, 'show', 'hide');
			}
		};

		const addPasswordToggle = (doc) => {
			doc.querySelectorAll('.password-toggle').forEach((element) => {
				let span = element.querySelector('.show-hide-ico');
				let input = element.querySelector('input');

				if (!span) {
					span = document.createElement('span');
					span.className = 'show-hide-ico show';
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
					const span = element.querySelector('.show-hide-ico');
					const input = element.querySelector('input');
					if (span.classList.contains('hide')) {
						input.type = 'password';
						toggleClass(span, 'hide', 'show');
					}
				});
			}
		});
	}
}
