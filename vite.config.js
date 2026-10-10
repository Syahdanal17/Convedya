import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'node:fs';

const entries = (dir, ext, skip = []) =>
    fs.existsSync(dir)
        ? fs
              .readdirSync(dir)
              .filter((file) => file.endsWith(ext) && !skip.includes(file))
              .map((file) => `${dir}/${file}`)
        : [];

const input = [
    ...entries('resources/css', '.css'),
    ...entries('resources/js', '.js', ['bootstrap.js']),
];

export default defineConfig({
    plugins: [
        laravel({
            input,
            refresh: true,
        }),
    ],

    server: {
        host: '0.0.0.0',
        port: 5173,
    },
});
