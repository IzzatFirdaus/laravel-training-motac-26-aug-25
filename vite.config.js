import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/welcome-demo.js',
                // Page-level entry points referenced via @vite in Blade
                'resources/js/pages/welcome.js',
                'resources/js/pages/home.js',
                'resources/js/pages/users-redirect.js',
                'resources/js/pages/inventories-index.js',
                'resources/js/pages/inventories-create.js',
                'resources/js/pages/inventories-edit.js',
                'resources/js/pages/excel-inventory-import.js',
                'resources/js/pages/vehicles-index.js',
                // Feature-level entry points referenced directly from partials
                'resources/js/features/application-form.js',
                'resources/js/features/footer.js',
            ],
            refresh: true,
        }),
    ],
});
