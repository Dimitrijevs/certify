import preset from "./vendor/filament/support/tailwind.config.preset";

export default {
    presets: [preset],
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./resources/views/tables/columns/*.blade.php",
        "./resources/views/components/*.blade.php",
        "./resources/views/filament/widgets/*.blade.php",
        "./resources/views/infolists/components/*.blade.php",
        "./resources/views/livewire/*.blade.php",
        "./resources/views/filament/resources/learning-test-resource/pages/*.blade.php",
        "./resources/views/filament/resources/**/pages/*.blade.php",
    ],
    plugins: [require("@tailwindcss/forms")],
};
