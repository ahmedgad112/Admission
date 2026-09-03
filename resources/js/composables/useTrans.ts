import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const translations = ref<Record<string, string>>({});

export function syncTranslations(
    locale: 'en' | 'ar' | undefined,
    dictionary: Record<string, string> | undefined,
): void {
    translations.value = dictionary ?? {};
}

export function trans(
    key: string,
    replace: Record<string, string | number> = {},
    fallback?: string,
): string {
    let value = translations.value[key] ?? fallback ?? key;

    for (const [name, replacement] of Object.entries(replace)) {
        value = value.replaceAll(`:${name}`, String(replacement));
    }

    return value;
}

export function useTrans(): {
    t: typeof trans;
    locale: 'en' | 'ar';
} {
    const page = usePage();

    syncTranslations(page.props.locale, page.props.translations);

    return {
        t: trans,
        locale: page.props.locale ?? 'en',
    };
}

export function useDocumentLocale(): void {
    const page = usePage();

    watch(
        () =>
            [
                page.props.locale,
                page.props.dir,
                page.props.translations,
            ] as const,
        ([locale, dir, dictionary]) => {
            syncTranslations(locale, dictionary);

            if (typeof document === 'undefined') {
                return;
            }

            document.documentElement.lang = locale ?? 'en';
            document.documentElement.dir = dir ?? 'ltr';
        },
        { immediate: true },
    );
}
