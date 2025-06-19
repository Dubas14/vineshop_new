import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';

// визначаємо __dirname, бо ES-модулі
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// 🚩 Підключаємо messages.js
import messages from './resources/js/lang/messages.js';

// 🔧 Вказуємо папку з Vue-файлами
const vueDir = './resources/js';
const locale = messages.en; // обираємо мову, яку перевіряти

// --- далі скрипт як був ---
function getVueFiles(dir) {
    let files = fs.readdirSync(dir);
    let vueFiles = [];

    for (let file of files) {
        const fullPath = path.join(dir, file);
        if (fs.statSync(fullPath).isDirectory()) {
            vueFiles = vueFiles.concat(getVueFiles(fullPath));
        } else if (file.endsWith('.vue')) {
            vueFiles.push(fullPath);
        }
    }

    return vueFiles;
}

function extractKeys(content) {
    const regex = /\$t\(['"`]([^'"`]+)['"`]\)/g;
    let match;
    const keys = new Set();
    while ((match = regex.exec(content)) !== null) {
        keys.add(match[1]);
    }
    return keys;
}

function findMissingKeys() {
    const files = getVueFiles(vueDir);
    const usedKeys = new Set();

    for (let file of files) {
        const content = fs.readFileSync(file, 'utf8');
        const keys = extractKeys(content);
        keys.forEach(k => usedKeys.add(k));
    }

    const missing = Array.from(usedKeys).filter(key => !locale.hasOwnProperty(key));

    if (missing.length === 0) {
        console.log('✅ Усі ключі присутні в messages.js -> en');
    } else {
        console.log('❌ Відсутні ключі:');
        missing.forEach(k => console.log('  -', k));
    }
}

findMissingKeys();
