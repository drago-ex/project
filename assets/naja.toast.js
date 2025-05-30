import { Toast } from 'bootstrap';
export default class ToastHandler {
	initialize(naja) {
		const showToast = (toastElement) => {
			const bsToast = new Toast(toastElement);
			bsToast.show();
			toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
		};

		naja.addEventListener('success', () => {
			document.querySelectorAll('.toast-container .toast').forEach(toastElement => {
				showToast(toastElement);
			});
		});

		document.querySelectorAll('.toast-container .toast').forEach(toastElement => {
			showToast(toastElement);
		});
	}
}
