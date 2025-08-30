// MYDS checker: lightweight scanner for legacy Bootstrap classes and hex colours
// Usage: node tools/check-myds.js

const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const scanDirs = [
    path.join(root, 'resources', 'views'),
    path.join(root, 'resources', 'css'),
    path.join(root, 'resources', 'sass'),
    path.join(root, 'public', 'css')
];

const legacyRegex = /\b(btn|alert|badge|input-group|input-group-text|form-control|text-danger|text-muted)\b/;
const hexColorRegex = /#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})\b/;

let errors = [];

function walk(dir) {
    if (!fs.existsSync(dir)) return;
    const items = fs.readdirSync(dir, { withFileTypes: true });
    for (const it of items) {
        const full = path.join(dir, it.name);
        if (it.isDirectory()) { walk(full); continue; }
        if (!it.isFile()) continue;
        const content = fs.readFileSync(full, 'utf8');

        if (full.endsWith('.blade.php')) {
            const classAttrRegex = /class\s*=\s*"([^"]*)"/g;
            let m;
            while ((m = classAttrRegex.exec(content)) !== null) {
                const classes = m[1].split(/\s+/).filter(Boolean);
                for (const c of classes) {
                    if (c.startsWith('myds-')) continue;
                    if (legacyRegex.test(c)) {
                        errors.push({ file: full, match: c });
                    }
                }
            }
        }

        if (/(\.css$|\.scss$)/i.test(full)) {
            const lines = content.split('\n');
            for (let i = 0; i < lines.length; i++) {
                if (hexColorRegex.test(lines[i])) {
                    errors.push({ file: full, line: i + 1, match: lines[i].trim() });
                }
            }
        }
    }
}

for (const d of scanDirs) walk(d);

if (errors.length === 0) {
    console.log('MYDS Lint: OK — no legacy classes or hex colours found (light scan)');
    process.exit(0);
}

console.error('MYDS Lint: Issues found:');
for (const e of errors) {
    console.error('-', e.file + (e.line ? ':' + e.line : ''), e.match);
}
process.exit(2);
