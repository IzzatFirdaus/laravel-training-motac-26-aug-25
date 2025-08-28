// Simple checker to enforce no Bootstrap .btn usage in Blade templates
// Usage: node tools/check-myds.cjs

const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const viewsDir = path.join(root, 'resources', 'views');

let errors = [];

function walk(dir) {
    const items = fs.readdirSync(dir, { withFileTypes: true });
    for (const it of items) {
        const full = path.join(dir, it.name);
        if (it.isDirectory()) {
            walk(full);
            continue;
        }
        if (!it.isFile()) continue;
        if (!full.endsWith('.blade.php')) continue;
        const content = fs.readFileSync(full, 'utf8');
    // Match class="..." values that contain a class starting with 'btn' but not 'myds-btn'
    // Use negative lookbehind to ignore 'myds-' prefixed classes.
    const regex = /class\s*=\s*"[^"]*(?<!myds-)\bbtn[\w-]*/g;
        let m;
        while ((m = regex.exec(content)) !== null) {
            errors.push({ file: full, match: m[0] });
        }
    }
}

walk(viewsDir);

if (errors.length === 0) {
    console.log('MYDS Lint: OK — no legacy .btn classes found in Blade views');
    process.exit(0);
}

console.error('MYDS Lint: Found legacy .btn usage in Blade views:');
for (const e of errors) {
    console.error('-', e.file, ':', e.match);
}
process.exit(2);
