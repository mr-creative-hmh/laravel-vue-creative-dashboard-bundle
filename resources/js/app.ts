import { createInertiaApp, router } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import type { SharedPageProps } from '@/types/auth';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return undefined;
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// Update document direction and language on Inertia navigation
router.on('navigate', (event) => {
    const props = event.detail.page.props as unknown as SharedPageProps;
    if (props.dir) {
        document.documentElement.dir = props.dir;
    }
    if (props.locale) {
        document.documentElement.lang = props.locale;
    }
});

// Initialize theme (light / dark mode)
initializeTheme();

// Listen for flash toast notifications
initializeFlashToast();
