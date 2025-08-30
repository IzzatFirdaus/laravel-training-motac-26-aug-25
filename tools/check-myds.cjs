// MYDS checker: enforce no legacy Bootstrap classes and no hardcoded hex colours
// Usage: node tools/check-myds.cjs

const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const scanPaths = [
    path.join(root, 'resources', 'views'),
    path.join(root, 'resources', 'sass'),
    path.join(root, 'resources', 'css'),
    path.join(root, 'resources', 'js'),
    path.join(root, 'public', 'css'),
];

const legacyClassPatterns = [
    /\bbtn\b/, /\balert\b/, /\bbadge\b/, /\binput-group\b/, /\binput-group-text\b/, /\bform-control\b/, /\btext-danger\b/, /\btext-muted\b/
];

const hexColorRegex = /#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})\b/;

let errors = [];

function readIfFile(full) {
    try {
        return fs.readFileSync(full, 'utf8');
    } catch (e) {
        return null;
    }
}

function walkAndScan(dir) {
    if (!fs.existsSync(dir)) return;
    const items = fs.readdirSync(dir, { withFileTypes: true });
    for (const it of items) {
        const full = path.join(dir, it.name);
        if (it.isDirectory()) {
            walkAndScan(full);
            continue;
        }
        if (!it.isFile()) continue;

        const content = readIfFile(full);
        if (!content) continue;

        // 1) In Blade views: detect legacy bootstrap classes that are not myds- prefixed
        if (full.endsWith('.blade.php')) {
            const classAttrRegex = /class\s*=\s*"([^"]*)"/g;
            let m;
            while ((m = classAttrRegex.exec(content)) !== null) {
                const classes = m[1].split(/\s+/).filter(Boolean);
                for (const c of classes) {
                    // skip myds prefixed classes
                    if (c.startsWith('myds-') || c.startsWith('myds')) continue;
                    // if class matches a legacy pattern, flag it
                    for (const p of legacyClassPatterns) {
                        if (p.test(c)) {
                            errors.push({ file: full, line: getLine(content, m.index), match: c, reason: 'legacy-class' });
                        }
                    }
                }
            }
        }

        // 2) In css/scss/js files: detect hardcoded hex color values (encourage tokens/variables)
        if (/(\.css$|\.scss$|\.js$)/i.test(full)) {
            const lines = content.split('\n');
            for (let i = 0; i < lines.length; i++) {
                if (hexColorRegex.test(lines[i])) {
                    // allow css custom property definitions (var(--...) may still contain hex in generator), but flag direct usages outside declarations
                    // if line contains '--' (variable def) it's probably a token definition; still flag so devs can review
                    errors.push({ file: full, line: i + 1, match: lines[i].trim(), reason: 'hex-color' });
                }
            }
        }
    }
}

function getLine(content, index) {
    const before = content.slice(0, index);
    return before.split('\n').length;
}

for (const p of scanPaths) {
    walkAndScan(p);
}

if (errors.length === 0) {
    console.log('MYDS Lint: OK — no legacy classes or hex colours found in scanned paths');
    process.exit(0);
}

console.error('MYDS Lint: Issues found:');
for (const e of errors) {
    console.error('-', e.file + ':' + (e.line || '?'), `[${e.reason}]`, e.match);
}
process.exit(2);
