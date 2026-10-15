import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/pages/vehicle.js',
                'resources/js/pages/driver.js',
                'resources/js/pages/employee.js',
                'resources/js/pages/user-management.js',
            ],
            refresh: true,
        }),
    ],
});