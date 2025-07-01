/* Imports */
import { LiveForm, Nette } from "live-form-validation";
import naja from "naja";
import { Alert } from "bootstrap";
import "../vendor/drago-ex/component/src/Drago/assets/naja.components"
import SubmitButtonDisable from "../vendor/drago-ex/form/src/Drago/Form/assets/naja.button";
import SpinnerExtension from "./naja.spinner";
import ErrorsExtension from "./naja.errors";
import HyperlinkDisable from "./naja.hyperlink";
import PasswordToggle from "./naja.password";
import "./app.scss"

window.LiveForm = LiveForm;
window.Nette = Nette;
window.naja = naja;

/* Initialize Nette (handles AJAX and form submission) */
/* https://doc.nette.org/en/forms */
Nette.initOnLoad();

/* Initialize Naja */
/* https://naja.js.org/#/quick-start */
naja.initialize();

/* Set options for LiveForm (error handling, form error styling, etc.) */
/* https://contributte.org/packages/contributte/live-form-validation.html#content */
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

/* Function to initialize alerts */
/* https://getbootstrap.com/docs/5.3/getting-started/introduction/*/
function initAlerts(selector) {
	document.querySelectorAll(selector).forEach((element) => {
		new Alert(element);
	});
}

/* Function to register all extensions */
function registerExtensions() {
	const extensions = [
		new ErrorsExtension(),
		new SubmitButtonDisable(),
		new SpinnerExtension(),
		new HyperlinkDisable(),
		new PasswordToggle(),
	];

	// Register each extension with Naja
	extensions.forEach(extension => naja.registerExtension(extension));
}

/* Register the extensions to Naja */
registerExtensions();

/* Initialize components after DOM is loaded */
document.addEventListener("DOMContentLoaded", () => {

	// Initialize alerts (Bootstrap)
	initAlerts('.alert');
});
