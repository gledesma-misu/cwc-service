import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";


export default defineConfig({
    plugins: [
        laravel(["resources/sass/app.scss", "resources/js/app.js"]),
        vue(),
        // Components({
        //     resolvers: [PrimeVueResolver()],
        // }),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js", // ✅ Use the full Vue build
        },
    },
});
