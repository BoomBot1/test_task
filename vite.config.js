import {defineConfig, loadEnv} from 'vite';
import laravel from 'laravel-vite-plugin'

export default defineConfig(({mode}) => {
    const env = loadEnv(mode, process.cwd());
    const PORT = `${env.VITE_PORT ?? '5173'}`;

    return {
        server: {
            host: '0.0.0.0',
            port: PORT,
            hmr: {
                host: '0.0.0.0',
            }
        },
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ]
    };
});
