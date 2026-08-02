import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

export function processPageToasts(pageProps: any): void {
    if (!pageProps || typeof pageProps !== 'object') return;

    const flash = pageProps.flash;
    const errors = pageProps.errors;

    // 1. Process flash messages
    if (flash && typeof flash === 'object') {
        if (flash.toast && flash.toast.message) {
            const message = String(flash.toast.message);
            const type = flash.toast.type || 'success';
            if (typeof (toast as any)[type] === 'function') {
                (toast as any)[type](message);
            } else {
                toast(message);
            }
            return;
        }

        if (flash.success) {
            toast.success(String(flash.success));
            return;
        }
        if (flash.error) {
            toast.error(String(flash.error));
            return;
        }
        if (flash.warning) {
            toast.warning(String(flash.warning));
            return;
        }
        if (flash.info) {
            toast.info(String(flash.info));
            return;
        }
        if (flash.status) {
            const statusMap: Record<string, string> = {
                'profile-information-updated': 'Profile information updated successfully.',
                'password-updated': 'Password updated successfully.',
                'two-factor-authentication-enabled': 'Two-factor authentication enabled successfully.',
                'two-factor-authentication-disabled': 'Two-factor authentication disabled successfully.',
                'recovery-codes-generated': 'Recovery codes regenerated.',
                'verification-link-sent': 'Verification link sent to your email address.',
            };
            const statusMsg = statusMap[flash.status] || String(flash.status);
            toast.success(statusMsg);
            return;
        }
    }

    // 2. Process form validation errors (if no explicit flash error was set)
    if (errors && typeof errors === 'object' && Object.keys(errors).length > 0) {
        const firstErrorKey = Object.keys(errors)[0];
        const firstErrorMsg = errors[firstErrorKey];
        if (firstErrorMsg && typeof firstErrorMsg === 'string') {
            toast.error(firstErrorMsg);
        }
    }
}

export function initializeFlashToast(): void {
    router.on('finish', (event: any) => {
        const page = event.page || event.detail?.page || (router as any).page;
        if (page?.props) {
            processPageToasts(page.props);
        }
    });

    router.on('httpException', (event: any) => {
        const status = event.response?.status || event.detail?.response?.status;
        if (status && status !== 403 && status !== 404) {
            toast.error('A server error occurred. Please try again.');
        }
    });

    router.on('networkError', () => {
        toast.error('Network connection error. Please check your connection.');
    });
}
