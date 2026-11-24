<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Administración de enlaces', href: '/admin' },
            { title: 'Crear enlace', href: '/admin/links/create' },
        ],
    },
});

const form = useForm({
    url: '',
    title: '',
    expires_at: '',
    password: '',
    is_active: true,
});

function submit() {
    form.post('/admin/links');
}
</script>

<template>
    <Head title="Crear enlace" />

    <div class="mx-auto max-w-2xl space-y-6 p-4 md:p-8">
        <div>
            <h1 class="text-2xl font-semibold">Crear enlace</h1>
            <p class="text-sm text-muted-foreground">
                Ingresa los datos del enlace que quieres guardar.
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
                    placeholder="https://ejemplo.com"
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
                    placeholder="Mi enlace"
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
                <Label for="password">
                    Contraseña de acceso (opcional)
                </Label>
                <Input
                    id="password"
                    name="password"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    minlength="8"
                />
                <p class="text-sm text-muted-foreground">
                    Si agregas una contraseña, debe tener al menos 8
                    caracteres.
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
                    {{ form.processing ? 'Guardando…' : 'Crear enlace' }}
                </Button>
                <Button variant="outline" as-child>
                    <Link href="/admin">Cancelar</Link>
                </Button>
            </div>
        </form>
    </div>
</template>
