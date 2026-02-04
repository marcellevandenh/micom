import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import vue2 from '@vitejs/plugin-vue2';

const targets = [];

const fs = require('fs');
const getFiles = (dir) => {
    try {
        return fs.readdirSync(dir).filter(file => {
            return fs.statSync(`${dir}/${file}`).isFile();
        });
    } catch(err) {
        return [];
    }
}

// CSS
getFiles('resources/css').forEach(filepath => {
    if (filepath[0] != '_') {
        targets.push('resources/css/' + filepath);
    }
})

// JS
getFiles('resources/js').forEach(filepath => {
    if (filepath[0] != '_') {
        targets.push('resources/js/' + filepath);
    }
})
getFiles('resources/js/partials').forEach(filepath => {
    if (filepath[0] != '_') {
        targets.push('resources/js/partials' + filepath);
    }
})

// SASS
getFiles('resources/sass').forEach(filepath => {
    if (filepath[0] != '_') {
        targets.push('resources/sass/' + filepath);
    }
})
getFiles('resources/sass/partials').forEach(filepath => {
    if (filepath[0] != '_') {
        targets.push('resources/sass/partials/' + filepath);
    }
})
getFiles('resources/sass/templates').forEach(filepath => {
    if (filepath[0] != '_') {
        targets.push('resources/sass/templates/' + filepath);
    }
})

export default defineConfig({
    plugins: [
        laravel({
            input: targets,
            refresh: true,
        }),
        // vue2(),
    ],
});
