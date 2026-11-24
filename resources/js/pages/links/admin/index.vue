<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

type LinkItem = {
    id: number;
    code: string;
    url: string;
    title: string | null;
    is_active: boolean;
    expires_at: string | null;
    has_password: boolean;
};

const props = defineProps<{
    links: LinkItem[];
    appUrl: string;
}>();

const deleteForm = useForm({});
const searchTerm = ref('');
const statusFilter = ref<'all' | 'active' | 'inactive'>('all');

const filteredLinks = computed(() => {
    const query = searchTerm.value.trim().toLowerCase();

    return props.links.filter(
        (link) =>
            `${link.title ?? ''} ${link.code} ${link.url}`
                .toLowerCase()
                .includes(query) &&
            (statusFilter.value === 'all' ||
                link.is_active === (statusFilter.value === 'active')),
    );
});

function shortUrl(code: string) {
    return `${props.appUrl}/link/${code}`;
}

function deleteLink(id: number) {
    if (window.confirm('¿Seguro que quieres eliminar este enlace?')) {
        deleteForm.delete(`/admin/links/${id}`);
    }
}
</script>

<template>
    <Head title="Administración de enlaces" />

    <div class="mx-auto max-w-5xl space-y-6 p-4 md:p-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">Administración de enlaces</h1>
                <p class="text-sm text-muted-foreground">
                    Administra los enlaces acortados.
                </p>
            </div>
            <Button as-child>
                <Link href="/admin/links/create">Crear enlace</Link>
            </Button>
        </div>

        <div
            v-if="links.length === 0"
            class="rounded-lg border border-dashed p-8 text-center"
        >
            <p class="font-medium">Todavía no hay enlaces.</p>
            <p class="mt-1 text-sm text-muted-foreground">
                Crea un enlace para empezar.
            </p>
        </div>

        <div v-else class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_12rem]">
                <label class="grid gap-2 text-sm font-medium">
                    Buscar por título o enlace
                    <Input
                        v-model="searchTerm"
                        type="search"
                        placeholder="Título, código o URL"
                    />
                </label>
                <label class="grid gap-2 text-sm font-medium">
                    Estado
                    <select
                        v-model="statusFilter"
                        class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                    >
                        <option value="all">Todos</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                    </select>
                </label>
            </div>

            <div
                v-if="filteredLinks.length === 0"
                class="rounded-lg border border-dashed p-8 text-center text-sm text-muted-foreground"
            >
                No hay enlaces que coincidan con la búsqueda y el filtro.
            </div>

            <div v-else class="overflow-hidden rounded-lg border">
                <div
                    v-for="link in filteredLinks"
                    :key="link.id"
                    class="flex flex-col gap-4 border-b p-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div class="min-w-0 space-y-1">
                        <Link
                            :href="`/admin/links/${link.id}`"
                            class="font-medium hover:underline"
                        >
                            {{ link.title || link.code }}
                        </Link>
                        <p class="truncate text-sm text-muted-foreground">
                            {{ link.url }}
                        </p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <a
                                :href="shortUrl(link.code)"
                                target="_blank"
                                rel="noopener noreferrer"
                                :class="
                                    link.is_active
                                        ? 'text-emerald-600 hover:underline'
                                        : 'text-foreground hover:underline'
                                "
                            >
                                {{ shortUrl(link.code) }}
                            </a>
                            <span
                                class="rounded-full px-2 py-1"
                                :class="
                                    link.is_active
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-muted text-muted-foreground'
                                "
                            >
                                {{ link.is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                            <span
                                class="rounded-full px-2 py-1"
                                :class="
                                    link.has_password
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-muted text-muted-foreground'
                                "
                            >
                                {{
                                    link.has_password
                                        ? 'Con contraseña'
                                        : 'Sin contraseña'
                                }}
                            </span>
                        </div>
                    </div>

                    <div class="flex shrink-0 gap-2">
                        <Button variant="outline" size="sm" as-child>
                            <Link :href="`/admin/links/${link.id}`">Ver</Link>
                        </Button>
                        <Button variant="outline" size="sm" as-child>
                            <Link :href="`/admin/links/${link.id}/edit`">
                                Editar
                            </Link>
                        </Button>
                        <Button
                            variant="destructive"
                            size="sm"
                            :disabled="deleteForm.processing"
                            @click="deleteLink(link.id)"
                        >
                            {{
                                deleteForm.processing
                                    ? 'Eliminando…'
                                    : 'Eliminar'
                            }}
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
