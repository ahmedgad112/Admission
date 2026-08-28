import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const skipWayfinder =
        mode === 'production' || ['1', 'true'].includes(env.SKIP_WAYFINDER_PLUGIN ?? '');
    const wayfinderCommand = env.WAYFINDER_PHP_COMMAND || 'php artisan wayfinder:generate';

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.ts'],
                refresh: true,
                fonts: [
                    bunny('Instrument Sans', {
                        weights: [400, 500, 600],
                    }),
                    bunny('Cairo', {
                        weights: [400, 500, 600, 700],
                    }),
                ],
            }),
            inertia(),
            tailwindcss(),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            ...(skipWayfinder
                ? []
                : [
                      wayfinder({
                          formVariants: true,
                          command: wayfinderCommand,
                      }),
                  ]),
        ],
        server: {
            watch: {
                ignored: [
                    '**/.agents/**',
                    '**/.claude/**',
                    '**/.cursor/**',
                    '**/.junie/**',
                    '**/vendor/**',
                ],
            },
        },
    };
});
