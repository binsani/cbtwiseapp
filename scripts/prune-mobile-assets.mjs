import { rm } from 'node:fs/promises';
import { resolve } from 'node:path';

// Windows installers are offered as a web download. They must not be embedded
// in the Android WebView assets, where they add hundreds of megabytes but cannot
// be used.
const downloads = resolve('android', 'app', 'src', 'main', 'assets', 'public', 'downloads');

await rm(downloads, { recursive: true, force: true });
console.log('Removed desktop-only downloads from the Android bundle.');
