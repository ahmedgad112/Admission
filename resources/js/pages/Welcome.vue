<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ClipboardList, MapPin, QrCode, ShieldCheck } from '@lucide/vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { trans, useDocumentLocale } from '@/composables/useTrans';
import { dashboard, login } from '@/routes';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

useDocumentLocale();
</script>

<template>
    <Head />

    <div class="relative min-h-screen bg-background text-foreground">
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_15%_0%,_rgba(45,191,163,0.14),_transparent_38%),radial-gradient(circle_at_90%_12%,_rgba(247,211,140,0.18),_transparent_28%)]"
        />
        <div
            class="relative mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-6"
        >
            <header class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="flex size-10 items-center justify-center rounded-2xl bg-primary text-primary-foreground"
                    >
                        <AppLogoIcon class="size-6" />
                    </div>
                    <div>
                        <p class="font-semibold">{{ appName }}</p>
                        <p class="text-xs text-muted-foreground">
                            {{ trans('welcome.tagline') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <LanguageSwitcher />
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="rounded-full bg-primary px-5 py-2 text-sm font-medium text-primary-foreground"
                    >
                        {{ trans('welcome.open_desk') }}
                    </Link>
                    <Link
                        v-else
                        :href="login()"
                        class="rounded-full bg-primary px-5 py-2 text-sm font-medium text-primary-foreground"
                    >
                        {{ trans('welcome.sign_in') }}
                    </Link>
                </div>
            </header>

            <main
                class="grid flex-1 items-center gap-12 py-16 lg:grid-cols-[1.1fr_0.9fr]"
            >
                <div class="space-y-6">
                    <p class="text-sm tracking-[0.24em] text-primary uppercase">
                        {{ trans('welcome.eyebrow') }}
                    </p>
                    <h1
                        class="max-w-xl text-4xl leading-tight font-semibold md:text-6xl"
                    >
                        {{ trans('welcome.headline') }}
                    </h1>
                    <p
                        class="max-w-lg text-base text-muted-foreground md:text-lg"
                    >
                        {{ trans('welcome.body') }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <Link
                            :href="
                                $page.props.auth.user ? dashboard() : login()
                            "
                            class="rounded-full bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground"
                        >
                            {{ trans('welcome.start') }}
                        </Link>
                        <Link
                            href="/attendance/scan"
                            class="rounded-full border border-border bg-card px-6 py-3 text-sm"
                        >
                            {{ trans('welcome.scan') }}
                        </Link>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border bg-card p-5 shadow-sm">
                        <QrCode class="mb-4 size-6 text-primary" />
                        <h2 class="font-medium">
                            {{ trans('welcome.qr_title') }}
                        </h2>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{ trans('welcome.qr_body') }}
                        </p>
                    </div>
                    <div class="rounded-3xl border bg-card p-5 shadow-sm">
                        <MapPin class="mb-4 size-6 text-primary" />
                        <h2 class="font-medium">
                            {{ trans('welcome.site_title') }}
                        </h2>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{ trans('welcome.site_body') }}
                        </p>
                    </div>
                    <div class="rounded-3xl border bg-card p-5 shadow-sm">
                        <ShieldCheck class="mb-4 size-6 text-primary" />
                        <h2 class="font-medium">
                            {{ trans('welcome.role_title') }}
                        </h2>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{ trans('welcome.role_body') }}
                        </p>
                    </div>
                    <div class="rounded-3xl border bg-card p-5 shadow-sm">
                        <ClipboardList class="mb-4 size-6 text-primary" />
                        <h2 class="font-medium">
                            {{ trans('welcome.tasks_title') }}
                        </h2>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{ trans('welcome.tasks_body') }}
                        </p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
