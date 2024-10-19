<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- Twoje istniejące meta i linki -->
    @livewireStyles
</head>
<body>
    <x-filament::layout>
        <!-- Twoja nawigacja i zawartość -->
        {{ $slot }}
    </x-filament::layout>

    <!-- Komponent globalnej nakładki ładowania -->
    <livewire:global-loading-overlay />

    @livewireScripts
</body>
</html>
