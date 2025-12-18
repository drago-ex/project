// scripts/npm-install.js
import { execSync } from "child_process";
import { existsSync } from "fs";

if (!existsSync("package.json")) {
	console.log("No package.json, skipping npm install.");
	process.exit(0);
}

const packages = [
	"./vendor/drago-ex/form",
	"./vendor/drago-ex/component",
];

for (const pkg of packages) {
	if (existsSync(pkg)) {
		console.log(`Installing ${pkg}...`);
		execSync(`npm install ${pkg}`, { stdio: "inherit" });
	}
}
