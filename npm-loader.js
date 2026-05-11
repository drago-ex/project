import { execSync } from "child_process";
import { existsSync, readdirSync, statSync } from "fs";
import { join } from "path";

const vendorDir = "./vendor/drago-ex/";

if (!existsSync("package.json")) {
	console.log("No package.json, skipping npm install.");
	process.exit(0);
}

// if vendor doesn't exist, do nothing at all
if (!existsSync(vendorDir)) {
	process.exit(0);
}

const packages = readdirSync(vendorDir).filter(name => {
	const pkgPath = join(vendorDir, name);
	if (!statSync(pkgPath).isDirectory()) return false;
	return existsSync(join(pkgPath, "src/Drago/assets"));
});

// if there is nothing to install, it's over
if (packages.length === 0) {
	process.exit(0);
}

for (const pkgName of packages) {
	const pkgPath = join(vendorDir, pkgName);
	const assetsPath = join(pkgPath, "src/Drago/assets");

	console.log(`Installing ${assetsPath}...`);

	// install where package.json is (not vendor root)
	if (existsSync(join(assetsPath, "package.json"))) {
		execSync(`npm install`, { cwd: assetsPath, stdio: "inherit" });
	}
}
