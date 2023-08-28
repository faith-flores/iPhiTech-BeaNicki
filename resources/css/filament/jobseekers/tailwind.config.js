import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/JobseekerPanel/**/*.php',
        './resources/views/filament/jobseeker-panel/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './resources/views/forms/components/**/*.blade.php',
    ],
}
