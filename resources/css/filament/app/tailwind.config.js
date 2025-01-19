import preset from "../../../../vendor/filament/filament/tailwind.config.preset";

export default {
    presets: [preset],
    content: [
        "./app/Filament/Clusters/Settings/**/*.php",
        "./resources/views/filament/clusters/settings/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./resources/views/filament/**/*.blade.php",
        "./resources/views/tables/columns/*.blade.php",
        "./resources/views/components/*.blade.php",
        "./resources/views/filament/widgets/*.blade.php",
        "./resources/views/filament/resources/**/pages/*.blade.php",
        "./resources/views/infolists/components/*.blade.php",
        "./resources/views/filament/resources/learning-test-resource/pages/*.blade.php",
        "./resources/views/filament/resources/learning-test-result-resource/pages/*.blade.php",
    ],
};
