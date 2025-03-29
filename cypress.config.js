import { defineConfig } from "cypress";

export default defineConfig({
    e2e: {
        loginUrl: "https://cn.test/app/login",
        registerUrl: "https://cn.test/app/register",
        baseUrl: "https://cn.test/app",
        landingUrl: "https://cn.test",

        setupNodeEvents(on, config) {
            // implement node event listeners here
        },
    },
});
