export type NotificationItem = {
    id: string;
    type: string;
    data: {
        title?: string;
        message?: string;
        action_url?: string;
        [key: string]: unknown;
    };
    read_at: string | null;
    created_at_human: string;
};

export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string | null;
    avatar_url?: string | null;
    is_active: boolean;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    roles?: string[];
    permissions?: string[];
    unread_notifications_count?: number;
    recent_notifications?: NotificationItem[];
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
    is_impersonating?: boolean;
    impersonator_name?: string | null;
};

export type FlashMessages = {
    success?: string | null;
    error?: string | null;
    warning?: string | null;
    info?: string | null;
};

export type SharedPageProps = {
    name: string;
    auth: Auth;
    flash: FlashMessages;
    locale: string;
    dir: 'ltr' | 'rtl';
    translations?: Record<string, string>;
    sidebarOpen: boolean;
};

/* @chisel-passkeys */
export type Passkey = {
    id: number;
    name: string;
    authenticator: string | null;
    created_at_diff: string;
    last_used_at_diff: string | null;
};
/* @end-chisel-passkeys */

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
