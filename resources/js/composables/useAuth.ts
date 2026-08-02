import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { SharedPageProps, User } from '@/types/auth';

export function useAuth() {
    const page = usePage<SharedPageProps>();

    const user = computed<User | null>(() => page.props.auth?.user ?? null);
    const roles = computed<string[]>(() => user.value?.roles ?? []);
    const permissions = computed<string[]>(() => user.value?.permissions ?? []);

    /**
     * Check if user is Super Admin
     */
    const isSuperAdmin = computed<boolean>(() => {
        return roles.value.includes('Super Admin');
    });

    /**
     * Check if user has a specific permission
     */
    function can(permission: string): boolean {
        if (isSuperAdmin.value) return true;
        return permissions.value.includes(permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    function canAny(perms: string[]): boolean {
        if (isSuperAdmin.value) return true;
        return perms.some((p) => permissions.value.includes(p));
    }

    /**
     * Check if user has a specific role
     */
    function hasRole(role: string): boolean {
        if (isSuperAdmin.value) return true;
        return roles.value.includes(role);
    }

    /**
     * Check if user has any of the given roles
     */
    function hasAnyRole(rList: string[]): boolean {
        if (isSuperAdmin.value) return true;
        return rList.some((r) => roles.value.includes(r));
    }

    return {
        user,
        roles,
        permissions,
        isSuperAdmin,
        can,
        canAny,
        hasRole,
        hasAnyRole,
    };
}
