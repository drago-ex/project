import { LiveForm, Nette } from "live-form-validation";
import naja from "naja";
import ErrorsExtension from "./naja.errors.js";
import "./base.scss";

/* Globals */
window.LiveForm = LiveForm;
window.Nette = Nette;
window.naja = naja;

/* Initialize Nette & Naja */
Nette.initOnLoad();
naja.initialize({ history: false });

/* Configure LiveForm */
LiveForm.setOptions({
	showMessageClassOnParent: false,
	messageParentClass: false,
	controlErrorClass: '',
	controlValidClass: '',
	messageErrorClass: 'invalid-feedback fw-semibold',
	enableHiddenMessageClass: 'show-hidden-error',
	disableLiveValidationClass: 'no-live-validation',
	disableShowValidClass: 'no-show-valid',
	messageTag: 'div',
	messageIdPostfix: '_message',
	messageErrorPrefix: '',
	showAllErrors: false,
	showValid: false,
	wait: false
});

/* Register Naja extensions */
naja.registerExtension(new ErrorsExtension());
