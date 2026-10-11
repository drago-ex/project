import { execSync } from 'child_process';
import { existsSync, readdirSync, statSync, readFileSync } from 'fs';
import { join } from 'path';

const vendorDir = './vendor/drago-ex/';
const frontendEntryFields = ['exports', 'main', 'module', 'browser', 'style', 'sass', 'unpkg', 'jsdelivr'];

if (!existsSync('package.json')) {
	console.log('No package.json, skipping npm install.');
	process.exit(0);
}

function hasFrontendEntry(value) {
	if (typeof value === 'string') {
		return /\.(?:[cm]?js|css|s[ac]ss)$/i.test(value);
	}

	if (Array.isArray(value)) {
		return value.some(hasFrontendEntry);
	}

	if (value !== null && typeof value === 'object') {
		return Object.values(value).some(hasFrontendEntry);
	}

	return false;
}

if (!existsSync(vendorDir)) {
	console.log('No vendor/drago-ex directory found, skipping local package install.');
	process.exit(0);
}

const packagesToInstall = readdirSync(vendorDir).filter(name => {
	const pkgPath = join(vendorDir, name);
	if (!statSync(pkgPath).isDirectory()) return false;

	const pkgJsonPath = join(pkgPath, 'package.json');
	if (existsSync(pkgJsonPath)) {
		try {
			const pkgJson = JSON.parse(readFileSync(pkgJsonPath, 'utf-8'));
			return !!pkgJson.name && frontendEntryFields.some(field => hasFrontendEntry(pkgJson[field]));
		} catch (e) {
			return false;
		}
	}
	return false;
}).map(name => join(vendorDir, name).replace(/\\/g, '/'));

if (packagesToInstall.length > 0) {
	console.log(`Installing and registering local packages: ${packagesToInstall.join(', ')}...`);

	// Vyčištění proměnných prostředí, aby npm si nestěžovalo na vnořený běh (EALLOWSCRIPTS)
	const cleanEnv = { ...process.env };
	delete cleanEnv.npm_config_allow_scripts;

	try {
		execSync(`npm install ${packagesToInstall.join(' ')} --ignore-scripts --no-audit --fund false`, {
			stdio: 'inherit',
			env: cleanEnv
		});
	} catch (error) {
		console.error('Failed to install local packages. Falling back to sequential install...');

		for (const pkg of packagesToInstall) {
			try {
				console.log(`Installing ${pkg}...`);
				execSync(`npm install ${pkg} --ignore-scripts --no-audit --fund false`, {
					stdio: 'inherit',
					env: cleanEnv
				});
			} catch (e) {
				console.error(`Failed to install ${pkg}:`, e.message);
			}
		}
	}
} else {
	console.log('No local drago-ex packages found to install.');
}
