<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

type LinkItem = {
    id: number;
    code: string;
    url: string;
    title: string | null;
    is_active: boolean | number;
    expires_at: string | null;
    has_password: boolean;
};

const props = defineProps<{
    link: LinkItem;
    appUrl: string;
}>();

const link = props.link;
const shortUrl = `${props.appUrl}/link/${link.code}`;
</script>

<template>
    <Head :title="link.title || link.code" />

    <div class="mx-auto max-w-3xl space-y-6 p-4 md:p-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">
                    {{ link.title || link.code }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    Detalles del enlace.
                </p>
            </div>
            <Button variant="outline" as-child>
                <Link :href="`/admin/links/${link.id}/edit`">
                    Editar enlace
                </Link>
            </Button>
        </div>

        <dl class="divide-y rounded-lg border">
            <div class="grid gap-1 p-4 sm:grid-cols-[12rem_1fr] sm:gap-4">
                <dt class="text-sm font-medium text-muted-foreground">
                    Código
                </dt>
                <dd class="break-all">
                    <a
                        :href="shortUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        :class="
                            link.is_active
                                ? 'text-emerald-600 underline underline-offset-4'
                                : 'text-foreground underline underline-offset-4'
                        "
                    >
                        {{ shortUrl }}
                    </a>
                </dd>
            </div>
            <div class="grid gap-1 p-4 sm:grid-cols-[12rem_1fr] sm:gap-4">
                <dt class="text-sm font-medium text-muted-foreground">
                    URL de destino
                </dt>
                <dd class="break-all">
                    <a
                        :href="link.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="underline underline-offset-4"
                    >
                        {{ link.url }}
                    </a>
                </dd>
            </div>
            <div class="grid gap-1 p-4 sm:grid-cols-[12rem_1fr] sm:gap-4">
                <dt class="text-sm font-medium text-muted-foreground">
                    Estado
                </dt>
                <dd
                    :class="
                        link.is_active
                            ? 'text-emerald-600'
                            : 'text-muted-foreground'
                    "
                >
                    {{ link.is_active ? 'Activo' : 'Inactivo' }}
                </dd>
            </div>
            <div class="grid gap-1 p-4 sm:grid-cols-[12rem_1fr] sm:gap-4">
                <dt class="text-sm font-medium text-muted-foreground">
                    Vencimiento
                </dt>
                <dd>
                    {{
                        link.expires_at
                            ? new Date(link.expires_at).toLocaleString('es')
                            : 'Sin vencimiento'
                    }}
                </dd>
            </div>
            <div class="grid gap-1 p-4 sm:grid-cols-[12rem_1fr] sm:gap-4">
                <dt class="text-sm font-medium text-muted-foreground">
                    Protección
                </dt>
                <dd
                    :class="
                        link.has_password
                            ? 'text-emerald-600'
                            : 'text-muted-foreground'
                    "
                >
                    {{
                        link.has_password
                            ? 'Con contraseña'
                            : 'Sin contraseña'
                    }}
                </dd>
            </div>
        </dl>

        <Button variant="outline" as-child>
            <Link href="/admin">Volver a administración</Link>
        </Button>
    </div>
</template>
