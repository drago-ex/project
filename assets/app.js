/* Imports */
import { LiveForm, Nette } from "live-form-validation";
import naja from "naja";
import { Alert } from "bootstrap";
import { PasswordToggle, SubmitButtonDisable } from "drago-form";
import { BootstrapComponents } from "drago-component";

import SpinnerExtension from "./class/naja.spinner";
import ErrorsExtension from "./class/naja.errors";
import HyperlinkDisable from ".class//naja.hyperlink";

import "./app.scss";

/* Globals */
window.LiveForm = LiveForm;
window.Nette = Nette;
window.naja = naja;

/* Init Nette */
Nette.initOnLoad();

/* Init Naja */
naja.initialize({ history: false });

/* LiveForm config */
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

/* Bootstrap alerts */
function initAlerts(selector) {
	document.querySelectorAll(selector).forEach(el => new Alert(el));
}

/* Register Naja extensions */
[
	new ErrorsExtension(),
	new SpinnerExtension(),
	new HyperlinkDisable(),
	new PasswordToggle(),
	new SubmitButtonDisable(),
	new BootstrapComponents()
].forEach(ext => naja.registerExtension(ext));

/* DOM ready */
document.addEventListener("DOMContentLoaded", () => {
	initAlerts('.alert');
});
