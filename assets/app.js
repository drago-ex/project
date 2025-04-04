/* Imports */
import { LiveForm, Nette } from "live-form-validation";
import naja from "naja";
import { Alert } from "bootstrap";
import "../vendor/drago-ex/components/src/Drago/assets/naja.components"
import SubmitButtonDisable from "./naja.button";
import SpinnerExtension from "./naja.spinner";
import ErrorsExtension from "./naja.errors";
import HyperlinkDisable from "./naja.hyperlink";
import "./app.scss"

window.LiveForm = LiveForm;
window.Nette = Nette;
window.naja = naja;

/* Initialize Nette (handles AJAX and form submission) */
Nette.initOnLoad();

/* Initialize Naja */
naja.initialize();

/* Set options for LiveForm (error handling, form error styling, etc.) */
LiveForm.setOptions({
	messageErrorClass: 'errors-live',
	messageParentClass: 'form-error',
	messageErrorPrefix: '',
	wait: 500,
});

/* Function to initialize alerts */
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
		new HyperlinkDisable()
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
