import { definePreset } from '@primeuix/themes';
import Aura from '@primeuix/themes/aura';

export const AvalonPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{blue.50}',
            100: '{blue.100}',
            200: '{blue.200}',
            300: '{blue.300}',
            400: '{blue.400}',
            500: '{blue.500}',
            600: '{blue.600}',
            700: '{blue.700}',
            800: '{blue.800}',
            900: '{blue.900}',
            950: '{blue.950}',
        },
        colorScheme: {
            light: {
                surface: {
                    0: '#ffffff',
                    50: '{slate.50}',
                    100: '{slate.100}',
                    200: '{slate.200}',
                    300: '{slate.300}',
                    400: '{slate.400}',
                    500: '{slate.500}',
                    600: '{slate.600}',
                    700: '{slate.700}',
                    800: '{slate.800}',
                    900: '{slate.900}',
                    950: '{slate.950}',
                },
                primary: {
                    color: '{primary.500}',
                    contrastColor: '#ffffff',
                    hoverColor: '{primary.600}',
                    activeColor: '{primary.700}',
                },
                content: {
                    background: '#ffffff',
                    hoverBackground: '{slate.100}',
                    borderColor: '{slate.200}',
                    color: '{slate.700}',
                    hoverColor: '{slate.900}',
                },
            },
        },
    },
});
