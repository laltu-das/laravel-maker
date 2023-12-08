/** @type {import('vite').UserConfig} */
export default {
    build: {
        assetsDir: "",
        rollupOptions: {
            input: ["resources/js/laravel-maker.js", "resources/css/laravel-maker.css"],
            output: {
                assetFileNames: "[name][extname]",
                entryFileNames: "[name].js",
            },
        },
    },
};