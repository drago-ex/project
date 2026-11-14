import naja from 'naja';
import { LiveForm, Nette } from 'live-form-validation';
import ErrorsHandler from '../naja/errors-handler.js';

/* Globals */
window.LiveForm = LiveForm;
window.Nette = Nette;
window.naja = naja;

/* Initialize Nette & Naja */
Nette.initOnLoad();
naja.initialize();

/* Configure LiveForm */
LiveForm.setOptions({
	showMessageClassOnParent: false,
	messageParentClass: 'form-error',
	controlErrorClass: '',
	controlValidClass: '',
	messageErrorClass: 'invalid-feedback fw-semibold',
	enableHiddenMessageClass: 'show-hidden-error',
	disableLiveValidationClass: 'no-live-validation',
	disableShowValidClass: 'no-show-valid',
	messageTag: 'span',
	messageIdPostfix: '_message',
	messageErrorPrefix: '',
	showAllErrors: true,
	showValid: false,
	wait: false
});

/*
 * LiveForm hides the message of a field only on a keydown. A value pasted, chosen from the autofill of the browser
 * or written by some phone keyboards fires none, so the message stayed until the blur, and the blur on the press of
 * the submit button removed it, the button moved up from under the mouse and the click missed it. Any new value
 * hides the message as typing does; the blur validates the field again.
 */
document.addEventListener('input', (event) => {
	const id = event.target instanceof Element ? event.target.getAttribute('data-lfv-message-id') : null;
	const messageEl = id ? document.getElementById(id) : null;
	if (messageEl) {
		messageEl.innerHTML = '';
		messageEl.className = '';
	}
});

/* Register Naja extensions */
export function registerNajaExtensions(...extensions) {
	extensions.forEach(Extension => {
		naja.registerExtension(new Extension());
	});
}

registerNajaExtensions(
	ErrorsHandler
);
