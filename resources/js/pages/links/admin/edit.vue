<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type LinkItem = {
    id: number;
    code: string;
    url: string;
    title: string | null;
    is_active: boolean | number | string;
    expires_at: string | null;
    has_password: boolean;
};

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Administración de enlaces', href: '/admin' },
            { title: 'Editar enlace', href: '#' },
        ],
    },
});

const props = defineProps<{
    link: LinkItem;
}>();

const form = useForm({
    code: props.link.code,
    url: props.link.url,
    title: props.link.title ?? '',
    expires_at: props.link.expires_at ?? '',
    password: '',
    is_active: Boolean(props.link.is_active),
});

function submit() {
    form.put(`/admin/links/${props.link.id}`);
}
</script>

<template>
    <Head title="Editar enlace" />

    <div class="mx-auto max-w-2xl space-y-6 p-4 md:p-8">
        <div>
            <h1 class="text-2xl font-semibold">Editar enlace</h1>
            <p class="text-sm text-muted-foreground">
                Actualiza los datos del enlace.
            </p>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="grid gap-2">
                <Label for="url">URL de destino</Label>
                <Input
                    id="url"
                    name="url"
                    v-model="form.url"
                    type="url"
                    required
                />
                <InputError :message="form.errors.url" />
            </div>

            <div class="grid gap-2">
                <Label for="title">Título (opcional)</Label>
                <Input
                    id="title"
                    name="title"
                    v-model="form.title"
                    maxlength="255"
                />
                <InputError :message="form.errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="expires_at">Vence el (opcional)</Label>
                <Input
                    id="expires_at"
                    name="expires_at"
                    v-model="form.expires_at"
                    type="datetime-local"
                />
                <InputError :message="form.errors.expires_at" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Cambiar contraseña (opcional)</Label>
                <Input
                    id="password"
                    name="password"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    minlength="4"
                    placeholder="Deja vacío para conservar la actual"
                />
                <p class="text-sm text-muted-foreground">
                    <span v-if="link.has_password">
                        Este enlace está protegido. Deja el campo vacío para
                        conservar la contraseña actual.
                    </span>
                    <span v-else>
                        Si agregas una contraseña, debe tener al menos 4
                        caracteres.
                    </span>
                </p>
                <InputError :message="form.errors.password" />
            </div>

            <div class="flex items-center gap-3">
                <input
                    id="is_active"
                    type="checkbox"
                    v-model="form.is_active"
                    class="size-4 rounded border-input"
                />
                <Label for="is_active">Enlace activo</Label>
            </div>

            <div class="flex flex-wrap gap-3">
                <Button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Guardando…' : 'Guardar cambios' }}
                </Button>
                <Button variant="outline" as-child>
                    <Link href="/admin">Cancelar</Link>
                </Button>
            </div>
        </form>
    </div>
</template>
