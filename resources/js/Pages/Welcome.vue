<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="w-full max-w-2xl px-6 text-center">
            <!-- Laravel Logo -->
            <svg viewBox="0 0 240 80" class="mx-auto mb-8 h-12 text-red-500" fill="currentColor">
                <path d="M31.096 17.493V26.6l16.849-9.634v9.208L31.096 35.81v9.27l23.869-13.674V13.676L31.096 17.493zM68.352 13.69l-6.435 3.172 5.813 3.899 6.435-3.172-5.813-3.899zM62.544 20.76l-3.695 1.822v9.114l3.695 1.822v-9.114zM72.37 27.21l-3.016-2.023-6.108 3.019-3.016-2.023-6.434 3.172 5.813 3.899 9.522-4.707 3.239 2.172 5.121-2.544-5.121-2.544v-3.421zM78.072 38.78l-9.534-4.709-3.239 2.172-9.534-4.709-3.239 2.172-9.534-4.709-3.239 2.172v9.27l3.239-1.607v-6.045l6.295 3.11-3.239 2.172 9.534 4.709 3.239-2.172 9.534 4.709 3.239-2.172 9.534 4.709 3.239-2.172v-9.27l-3.239 1.607v6.045z" />
            </svg>

            <h1 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">
                Welcome to Laravel + Inertia + Vue
            </h1>

            <p class="mb-8 text-gray-600 dark:text-gray-300">
                Laravel {{ laravelVersion }} · PHP {{ phpVersion }}
            </p>

            <Link
                href="https://laravel.com"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
            >
                Laravel Docs
            </Link>
        </div>
    </div>
</template>
