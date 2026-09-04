<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { trans } from '@/composables/useTrans';
import { edit } from '@/routes/security';

type Props = {
    passwordRules: string;
};

const props = defineProps<Props>();
const page = usePage();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'settings.security.title',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head :title="trans('settings.security.title')" />

    <h1 class="sr-only">{{ trans('settings.security.title') }}</h1>

    <div class="space-y-6">
        <div
            v-if="page.props.auth.user?.must_change_password"
            class="rounded-2xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-800 dark:text-amber-200"
        >
            {{ trans('settings.security.must_change') }}
        </div>
        <Heading
            variant="small"
            :title="trans('settings.security.heading')"
            :description="trans('settings.security.description')"
        />

        <Form
            v-bind="SecurityController.update.form()"
            :options="{
                preserveScroll: true,
            }"
            reset-on-success
            :reset-on-error="[
                'password',
                'password_confirmation',
                'current_password',
            ]"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="current_password">{{
                    trans('settings.security.current')
                }}</Label>
                <PasswordInput
                    id="current_password"
                    name="current_password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                    :placeholder="trans('settings.security.current')"
                />
                <InputError :message="errors.current_password" />
            </div>

            <div class="grid gap-2">
                <Label for="password">{{
                    trans('settings.security.new')
                }}</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    :placeholder="trans('settings.security.new')"
                    :passwordrules="props.passwordRules"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">{{
                    trans('settings.security.confirm')
                }}</Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    :placeholder="trans('settings.security.confirm')"
                    :passwordrules="props.passwordRules"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <div class="flex items-center gap-4">
                <Button
                    :disabled="processing"
                    data-test="update-password-button"
                >
                    {{ trans('common.save') }}
                </Button>
            </div>
        </Form>
    </div>
</template>
