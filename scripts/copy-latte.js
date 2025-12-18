import fs from "fs";
import ncp from "ncp";

const copies = [
	{ src: "vendor/drago-ex/form/src/Drago/Form/assets/latte/", dest: "app/Core/Widget/" },
	{ src: "vendor/drago-ex/component/src/Drago/assets/latte/", dest: "app/Core/Widget/" }
];

copies.forEach(({ src, dest }) => {
	if (!fs.existsSync(src)) {
		console.warn(`The source folder does not exist: ${src}`);
		return;
	}

	if (!fs.existsSync(dest)) {
		fs.mkdirSync(dest, { recursive: true });
	}

	console.log(`Copying from ${src} → ${dest}`);
	ncp(src, dest, { clobber: false }, (err) => {
		if (err) {
			console.error(`Copy error ${src}:`, err);
		} else {
			console.log(`Done: ${src}`);
		}
	});
});
