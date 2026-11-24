<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    ArrowUpRight,
    ChartNoAxesCombined,
    Check,
    Link2,
    MousePointerClick,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import { Button } from '@/components/ui/button';
import { dashboard, login, register } from '@/routes';
</script>

<template>
    <Head title="Acortador de enlaces" />

    <div class="min-h-screen bg-background">
        <header class="border-b bg-background/90">
            <nav
                class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8"
                aria-label="Navegación principal"
            >
                <Link href="/" aria-label="Ir al inicio">
                    <AppLogo />
                </Link>

                <div class="flex items-center gap-2 sm:gap-3">
                    <template v-if="$page.props.auth.user">
                        <Button variant="ghost" as-child>
                            <Link href="/links">Mis enlaces</Link>
                        </Button>
                        <Button as-child>
                            <Link :href="dashboard()">
                                Dashboard
                                <ArrowRight class="ml-2 size-4" />
                            </Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button variant="ghost" as-child>
                            <Link :href="login()">Iniciar sesión</Link>
                        </Button>
                        <Button as-child>
                            <Link :href="register()">Crear cuenta</Link>
                        </Button>
                    </template>
                </div>
            </nav>
        </header>

        <main>
            <section
                class="relative isolate overflow-hidden border-b bg-gradient-to-br from-primary/5 via-background to-emerald-500/10"
            >
                <div
                    aria-hidden="true"
                    class="absolute -right-32 -top-40 -z-10 size-[34rem] rounded-full bg-primary/10 blur-3xl"
                />
                <div
                    class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-2 lg:px-8 lg:py-24"
                >
                    <div>
                        <p
                            class="inline-flex items-center gap-2 rounded-full border bg-background/80 px-3 py-1 text-sm font-medium text-primary"
                        >
                            <span class="size-2 rounded-full bg-primary" />
                            Proyecto de práctica
                        </p>

                        <h1
                            class="mt-6 max-w-2xl text-4xl leading-tight font-semibold tracking-tight sm:text-5xl lg:text-6xl"
                        >
                            Pega el enlace.
                            <span class="text-primary">Comparte el corto.</span>
                        </h1>

                        <p
                            class="mt-6 max-w-xl text-lg leading-8 text-muted-foreground"
                        >
                            Guarda tus enlaces y revisa cuántos clics recibe
                            cada uno.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <template v-if="$page.props.auth.user">
                                <Button size="lg" as-child>
                                    <Link :href="dashboard()">
                                        Ir a mi dashboard
                                        <ArrowRight class="ml-2 size-4" />
                                    </Link>
                                </Button>
                                <Button size="lg" variant="outline" as-child>
                                    <Link href="/links">Ver mis enlaces</Link>
                                </Button>
                            </template>
                            <template v-else>
                                <Button size="lg" as-child>
                                    <Link :href="register()">
                                        Crear una cuenta
                                        <ArrowRight class="ml-2 size-4" />
                                    </Link>
                                </Button>
                                <Button size="lg" variant="outline" as-child>
                                    <Link :href="login()">
                                        Ya tengo una cuenta
                                    </Link>
                                </Button>
                            </template>
                        </div>

                        <ul
                            class="mt-8 flex flex-wrap gap-x-6 gap-y-3 text-sm text-muted-foreground"
                        >
                            <li class="flex items-center gap-2">
                                <Check class="size-4 text-primary" />
                                Fáciles de compartir
                            </li>
                            <li class="flex items-center gap-2">
                                <Check class="size-4 text-primary" />
                                Clics a la vista
                            </li>
                        </ul>
                    </div>

                    <div class="mx-auto w-full max-w-xl">
                        <div
                            class="overflow-hidden rounded-3xl border bg-card shadow-xl shadow-primary/5"
                        >
                            <div
                                class="flex items-center justify-between border-b px-5 py-4"
                            >
                                <div class="flex items-center gap-3">
                                    <span
                                        class="grid size-10 place-items-center rounded-xl bg-primary/10 text-primary"
                                    >
                                        <Link2 class="size-5" />
                                    </span>
                                    <div>
                                        <p class="font-medium">
                                            De largo a corto
                                        </p>
                                        <p class="text-xs text-muted-foreground">
                                            Ejemplo
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-400"
                                >
                                    Así quedaría
                                </span>
                            </div>

                            <div class="space-y-5 p-5 sm:p-7">
                                <div class="space-y-2">
                                    <p
                                        class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                                    >
                                        Enlace original
                                    </p>
                                    <div
                                        class="rounded-xl border bg-muted/40 px-4 py-3 text-sm text-muted-foreground break-all"
                                    >
                                        ejemplo.com/articulos/como-empezar
                                    </div>
                                </div>

                                <div class="flex justify-center">
                                    <span
                                        class="grid size-9 place-items-center rounded-full border bg-background text-primary"
                                    >
                                        <ArrowRight
                                            class="size-4 rotate-90 sm:rotate-0"
                                        />
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    <p
                                        class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                                    >
                                        Enlace corto
                                    </p>
                                    <div
                                        class="flex items-center justify-between gap-3 rounded-xl border border-primary/20 bg-primary/5 px-4 py-3"
                                    >
                                        <span
                                            class="truncate text-sm font-semibold text-primary"
                                        >
                                            linksST/x7Kp2
                                        </span>
                                        <Link2
                                            class="size-4 shrink-0 text-primary"
                                        />
                                    </div>
                                </div>

                                <p
                                    class="border-t pt-4 text-xs leading-5 text-muted-foreground"
                                >
                                    El código es solo un ejemplo.
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-4 flex items-center justify-center gap-2 text-xs text-muted-foreground"
                        >
                            <MousePointerClick class="size-4" />
                            Crea y administra tus enlaces desde tu cuenta
                        </div>
                    </div>
                </div>
            </section>

            <section
                id="funciones"
                class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20"
            >
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold text-primary">
                        Qué puedes hacer
                    </p>
                    <h2 class="mt-3 text-3xl font-semibold tracking-tight">
                        Tus enlaces, en un solo lugar
                    </h2>
                    <p class="mt-3 leading-7 text-muted-foreground">
                        Acorta una dirección, guárdala y revisa sus clics.
                    </p>
                </div>

                <div class="mt-10 grid gap-4 md:grid-cols-3">
                    <article class="rounded-2xl border bg-card p-6">
                        <span
                            class="grid size-11 place-items-center rounded-xl bg-primary/10 text-primary"
                        >
                            <Link2 class="size-5" />
                        </span>
                        <h3 class="mt-5 font-semibold">
                            Enlaces más cortos
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-muted-foreground">
                            Pega una dirección larga y compártela usando un
                            código breve.
                        </p>
                    </article>

                    <article class="rounded-2xl border bg-card p-6">
                        <span
                            class="grid size-11 place-items-center rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400"
                        >
                            <ArrowUpRight class="size-5" />
                        </span>
                        <h3 class="mt-5 font-semibold">
                            Tenlos a mano
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-muted-foreground">
                            Consulta los enlaces que ya creaste y activa o
                            desactiva cada uno cuando quieras.
                        </p>
                    </article>

                    <article class="rounded-2xl border bg-card p-6">
                        <span
                            class="grid size-11 place-items-center rounded-xl bg-violet-500/10 text-violet-600 dark:text-violet-400"
                        >
                            <ChartNoAxesCombined class="size-5" />
                        </span>
                        <h3 class="mt-5 font-semibold">
                            Revisa las visitas
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-muted-foreground">
                            Consulta cuántas veces han abierto cada enlace.
                        </p>
                    </article>
                </div>
            </section>

            <section id="alcance" class="border-y bg-muted/30">
                <div
                    class="mx-auto grid max-w-7xl gap-6 px-4 py-12 sm:px-6 md:grid-cols-[auto_1fr] md:items-start lg:px-8"
                >
                    <span
                        class="grid size-11 place-items-center rounded-xl bg-amber-500/10 text-amber-700 dark:text-amber-400"
                    >
                        <Check class="size-5" />
                    </span>
                    <div>
                        <h2 class="text-lg font-semibold">
                            Un proyecto de práctica
                        </h2>
                        <p class="mt-2 max-w-4xl text-sm leading-6 text-muted-foreground">
                            Este acortador se creó para aprender y probar.
                            Está pensado para uso personal y proyectos pequeños;
                            no lo uses con información sensible, en servicios
                            críticos ni para grandes volúmenes de visitas.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <footer
            class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-8 text-sm text-muted-foreground sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8"
        >
            <p>Acortador de enlaces · Proyecto de práctica</p>
            <p>Para uso personal y proyectos pequeños</p>
        </footer>
    </div>
</template>
