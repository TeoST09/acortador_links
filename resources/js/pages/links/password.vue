<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    link: {
        code: string;
        title: string | null;
    };
    redirectUrl?: string;
}>();
const link = props.link;

const form = useForm({
    password: '',
});

function submit() {
    form.post(`/link/${props.link.code}/password`, {
        onSuccess: (page) => {
            form.reset('password');

            if (typeof page.props.redirectUrl === 'string') {
                window.location.assign(page.props.redirectUrl);
            }
        },
    });
}
</script>

<template>
    <Head :title="`Enlace protegido: ${link.title || link.code}`" />

    <main class="flex min-h-screen items-center justify-center p-4">
        <section class="w-full max-w-md space-y-6 rounded-lg border p-6">
            <div class="space-y-2">
                <h1 class="text-2xl font-semibold">Enlace protegido</h1>
                <p class="text-sm text-muted-foreground">
                    Ingresa la contraseña para continuar
                    <span v-if="link.title">a {{ link.title }}</span>.
                </p>
            </div>

            <form class="space-y-5" @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="password">Contraseña</Label>
                    <Input
                        id="password"
                        v-model="form.password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        autofocus
                        :disabled="form.processing"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <Button
                    type="submit"
                    class="w-full"
                    :disabled="form.processing || !form.password"
                >
                    {{
                        form.processing
                            ? 'Verificando…'
                            : 'Acceder al enlace'
                    }}
                </Button>
            </form>
        </section>
    </main>
</template>
