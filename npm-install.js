import { execSync } from "child_process";
import { existsSync, readdirSync, statSync } from "fs";
import { join } from "path";

const vendorDir = "./vendor/drago-ex/";
if (!existsSync("package.json")) {
	console.log("No package.json, skipping npm install.");
	process.exit(0);
}

const packages = readdirSync(vendorDir).filter(name => {
	const pkgPath = join(vendorDir, name);
	if (!statSync(pkgPath).isDirectory()) return false;
	return existsSync(join(pkgPath, "src/Drago/assets"));
});

for (const pkgName of packages) {
	const pkgPath = join(vendorDir, pkgName);
	console.log(`Installing ${pkgPath}...`);
	execSync(`npm install ${pkgPath}`, { stdio: "inherit" });
}
