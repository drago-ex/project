import { Spinner as PageSpinner } from 'compostrap/spinner';

/* The line at the top of the window while an AJAX request runs */
export default class Spinner {
	initialize(naja) {
		const spinner = new PageSpinner();

		naja.addEventListener('start', () => spinner.show());
		naja.addEventListener('complete', () => spinner.hide());
	}
}
