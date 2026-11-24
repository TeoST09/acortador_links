<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    ArrowRight,
    ArrowUpRight,
    ChartNoAxesCombined,
    Link2,
    MousePointerClick,
    Plus,
} from '@lucide/vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';

type DashboardLink = {
    id: number;
    code: string;
    title: string | null;
    clicks: number;
    is_active: boolean;
};

type Analytics = {
    total_links: number;
    active_links: number;
    total_clicks: number;
    clicks_by_day: { date: string; clicks: number }[];
    top_links: DashboardLink[];
    recent_links: DashboardLink[];
};

const props = defineProps<{
    analytics: Analytics;
    appUrl: string;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Dashboard', href: dashboard() }],
    },
});


const weeklyClicks = computed(() => {
    let total = 0;

    for (const day of props.analytics.clicks_by_day) {
        total += day.clicks;
    }

    return total;
});

const maxDailyClicks = computed(() => {
    let max = 1;

    for (const day of props.analytics.clicks_by_day) {
        if (day.clicks > max) {
            max = day.clicks;
        }
    }

    return max;
});


function formatNumber(value: number): string {
    return new Intl.NumberFormat('es').format(value);
}

function dayLabel(date: string): string {
    return new Date(`${date}T00:00:00`).toLocaleDateString('es', {
        weekday: 'short',
    });
}

function barHeight(clicks: number): string {
    return `${Math.max(4, (clicks / maxDailyClicks.value) * 100)}%`;
}

function topLinkWidth(clicks: number): string {
    const mostClicks = props.analytics.top_links[0]?.clicks ?? 0;

    return mostClicks === 0 ? '0%' : `${(clicks / mostClicks) * 100}%`;
}

function shortUrl(code: string): string {
    return `${props.appUrl}/link/${code}`;
}
</script>

<template>
    <Head title="Analíticas" />

    <main class="mx-auto max-w-7xl space-y-8 p-4 md:p-8">
        <section
            class="relative overflow-hidden rounded-3xl border bg-card px-6 py-8 shadow-sm md:px-9"
        >
            <div
                aria-hidden="true"
                class="absolute -right-16 -top-28 size-80 rounded-full bg-primary/10 blur-3xl"
            />
            <div
                class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between"
            >
                <div class="max-w-2xl">
                    <p
                        class="mb-4 inline-flex items-center gap-2 rounded-full border bg-background/80 px-3 py-1 text-xs font-medium text-muted-foreground"
                    >
                        <ChartNoAxesCombined class="size-4 text-primary" />
                        Rendimiento de tus enlaces
                    </p>
                    <h1 class="text-3xl font-semibold tracking-tight md:text-4xl">
                        Todo tu alcance, de un vistazo.
                    </h1>
                    <p class="mt-3 text-sm leading-6 text-muted-foreground md:text-base">
                        Revisa tus clics, descubre qué enlaces funcionan mejor
                        y mantén tu contenido organizado.
                    </p>
                </div>

                <Button as-child class="shrink-0">
                    <Link href="/links/create">
                        <Plus class="mr-2 size-4" />
                        Crear enlace
                    </Link>
                </Button>
            </div>
        </section>

        <section
            aria-label="Resumen de estadísticas"
            class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"
        >
            <article class="rounded-2xl border bg-card p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">
                            Enlaces creados
                        </p>
                        <p class="mt-3 text-3xl font-semibold">
                            {{ formatNumber(analytics.total_links) }}
                        </p>
                    </div>
                    <span
                        class="grid size-10 place-items-center rounded-xl bg-primary/10 text-primary"
                    >
                        <Link2 class="size-5" />
                    </span>
                </div>
            </article>
            <article class="rounded-2xl border bg-card p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">
                            Clics totales
                        </p>
                        <p class="mt-3 text-3xl font-semibold">
                            {{ formatNumber(analytics.total_clicks) }}
                        </p>
                    </div>
                    <span
                        class="grid size-10 place-items-center rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400"
                    >
                        <MousePointerClick class="size-5" />
                    </span>
                </div>
            </article>
            <article class="rounded-2xl border bg-card p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">
                            Enlaces activos
                        </p>
                        <p class="mt-3 text-3xl font-semibold">
                            {{ formatNumber(analytics.active_links) }}
                        </p>
                    </div>
                    <span
                        class="grid size-10 place-items-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"
                    >
                        <Activity class="size-5" />
                    </span>
                </div>
            </article>
            <article class="rounded-2xl border bg-card p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">
                            Clics esta semana
                        </p>
                        <p class="mt-3 text-3xl font-semibold">
                            {{ formatNumber(weeklyClicks) }}
                        </p>
                    </div>
                    <span
                        class="grid size-10 place-items-center rounded-xl bg-violet-500/10 text-violet-600 dark:text-violet-400"
                    >
                        <ChartNoAxesCombined class="size-5" />
                    </span>
                </div>
            </article>
        </section>

        <section class="grid gap-5 xl:grid-cols-[1.7fr_1fr]">
            <article class="rounded-2xl border bg-card p-5 shadow-sm md:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold">Actividad de clics</h2>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Últimos 7 días
                        </p>
                    </div>
                    <div class="rounded-xl bg-muted px-3 py-2 text-right">
                        <p class="text-xs text-muted-foreground">Esta semana</p>
                        <p class="font-semibold">
                            {{ formatNumber(weeklyClicks) }}
                        </p>
                    </div>
                </div>

                <div
                    v-if="analytics.clicks_by_day.length"
                    class="mt-8 grid h-48 grid-cols-7 items-end gap-2 sm:gap-4"
                    role="img"
                    aria-label="Clics diarios de los últimos siete días"
                >
                    <div
                        v-for="day in analytics.clicks_by_day"
                        :key="day.date"
                        class="flex h-full flex-col items-center justify-end gap-2"
                    >
                        <span class="text-xs text-muted-foreground">
                            {{ formatNumber(day.clicks) }}
                        </span>
                        <div
                            class="flex h-full w-full items-end rounded-t-lg bg-muted"
                        >
                            <div
                                class="w-full rounded-t-lg bg-primary"
                                :style="{ height: barHeight(day.clicks) }"
                            />
                        </div>
                        <span class="text-xs capitalize text-muted-foreground">
                            {{ dayLabel(day.date) }}
                        </span>
                    </div>
                </div>
                <div
                    v-else
                    class="mt-8 flex min-h-48 flex-col items-center justify-center rounded-xl border border-dashed px-6 text-center"
                >
                    <MousePointerClick class="mb-3 size-6 text-primary" />
                    <p class="font-medium">Todavía no hay clics</p>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Las visitas a tus enlaces aparecerán aquí.
                    </p>
                </div>
            </article>

            <article class="rounded-2xl border bg-card p-5 shadow-sm md:p-6">
                <h2 class="text-lg font-semibold">Enlaces destacados</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Los que más visitas reciben.
                </p>

                <div v-if="analytics.top_links.length" class="mt-5 space-y-5">
                    <div
                        v-for="link in analytics.top_links"
                        :key="link.id"
                        class="space-y-2"
                    >
                        <div class="flex justify-between gap-3 text-sm">
                            <Link
                                :href="`/links/${link.id}`"
                                class="truncate font-medium hover:text-primary"
                            >
                                {{ link.title || link.code }}
                            </Link>
                            <span class="shrink-0 text-muted-foreground">
                                {{ formatNumber(link.clicks) }}
                            </span>
                        </div>
                        <div class="h-1.5 rounded-full bg-muted">
                            <div
                                class="h-1.5 rounded-full bg-primary"
                                :style="{ width: topLinkWidth(link.clicks) }"
                            />
                        </div>
                    </div>
                    <Link
                        href="/links"
                        class="inline-flex items-center gap-1 text-sm font-medium text-primary hover:underline"
                    >
                        Ver todos
                        <ArrowRight class="size-4" />
                    </Link>
                </div>
                <div
                    v-else
                    class="mt-5 flex min-h-48 flex-col items-center justify-center rounded-xl border border-dashed px-5 text-center"
                >
                    <Link2 class="mb-3 size-6 text-muted-foreground" />
                    <p class="font-medium">Aún no hay enlaces</p>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Crea y comparte un enlace para ver sus resultados.
                    </p>
                </div>
            </article>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-card shadow-sm">
            <div
                class="flex items-center justify-between gap-4 border-b px-5 py-5 md:px-6"
            >
                <div>
                    <h2 class="text-lg font-semibold">Enlaces recientes</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Tus últimos enlaces y sus clics.
                    </p>
                </div>
                <Button variant="outline" size="sm" as-child>
                    <Link href="/links">
                        Ver todos
                        <ArrowUpRight class="ml-2 size-4" />
                    </Link>
                </Button>
            </div>

            <div v-if="analytics.recent_links.length" class="divide-y">
                <div
                    v-for="link in analytics.recent_links"
                    :key="link.id"
                    class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between md:px-6"
                >
                    <div class="min-w-0 flex-1">
                        <Link
                            :href="`/links/${link.id}`"
                            class="block truncate text-sm font-medium hover:text-primary"
                        >
                            {{ link.title || link.code }}
                        </Link>
                        <p class="mt-1 truncate text-xs text-muted-foreground">
                            {{ shortUrl(link.code) }}
                        </p>
                    </div>
                    <div
                        class="flex items-center justify-between gap-4 sm:justify-end"
                    >
                        <span
                            class="rounded-full px-2.5 py-1 text-xs font-medium"
                            :class="
                                link.is_active
                                    ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400'
                                    : 'bg-muted text-muted-foreground'
                            "
                        >
                            {{ link.is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1 text-sm font-medium"
                        >
                            <MousePointerClick class="size-3.5 text-muted-foreground" />
                            {{ formatNumber(link.clicks) }}
                        </span>
                    </div>
                </div>
            </div>
            <div v-else class="px-6 py-12 text-center">
                <p class="font-medium">Tu primer enlace está a un clic</p>
                <p class="mt-1 text-sm text-muted-foreground">
                    Crea un enlace corto para comenzar.
                </p>
                <Button as-child class="mt-5">
                    <Link href="/links/create">Crear enlace</Link>
                </Button>
            </div>
        </section>
    </main>
</template>
