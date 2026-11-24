<?php

namespace App\Http\Controllers;

use App\Models\Links;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;

class LinksController extends Controller
{
    public function index(Request $request): Response
    {

        $validate = $request->validate([
            'search' => ['nullable', 'string'],
        ]);

        $trim = trim($validate['search'] ?? '');

        if ($trim !== '') {
            $links = Links::query()
                ->where('user_id', auth()->id())
                ->where(function ($query) use ($trim) {
                    $query->where('code', 'like', "%{$trim}%")
                        ->orWhere('url', 'like', "%{$trim}%")
                        ->orWhere('title', 'like', "%{$trim}%");
                })
                ->orderByDesc('id')
                ->get()
                ->map(fn (Links $link): array => [
                    'id' => $link->id,
                    'code' => $link->code,
                    'url' => $link->url,
                    'title' => $link->title,
                    'is_active' => (bool) $link->is_active,
                    'expires_at' => $link->expires_at,
                    'has_password' => filled($link->password),
                ])
                ->all();
        } else {
            $links = [];
        }

        $links = Links::query()
            ->where('user_id', auth()->id())
            ->orderByDesc('id')
            ->get()
            ->map(fn (Links $link): array => [
                'id' => $link->id,
                'code' => $link->code,
                'url' => $link->url,
                'title' => $link->title,
                'is_active' => (bool) $link->is_active,
                'expires_at' => $link->expires_at,
                'has_password' => filled($link->password),
            ])
            ->all();

        return Inertia::render('links/index', [
            'appUrl' => rtrim(config('app.url'), '/'),
            'links' => $links,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('links/create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
            'title' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'expires_at' => ['nullable', 'date'],
            'password' => ['nullable', 'string', 'max:255'],
        ]);

        Links::create([
            'user_id' => auth()->id(),
            'code' => \Str::random(6),
            'url' => $validated['url'],
            'title' => $validated['title'] ?? null,
            'is_active' => $validated['is_active'],
            'expires_at' => $validated['expires_at'] ?? null,
            'password' => filled($validated['password'] ?? null)
                ? Hash::make($validated['password'])
                : null,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Enlace creado correctamente.',
        ]);

        return to_route('links.index');
    }

    public function show(Links $link): Response
    {
        $user = auth()->user()->id;

        if ($link->user_id !== $user) {
            abort(403);
        }

        return Inertia::render('links/show', [
            'appUrl' => rtrim(config('app.url'), '/'),
            'link' => [
                'id' => $link->id,
                'code' => $link->code,
                'url' => $link->url,
                'title' => $link->title,
                'is_active' => (bool) $link->is_active,
                'expires_at' => $link->expires_at,
                'has_password' => filled($link->password),
            ],
        ]);
    }

    public function edit(Links $link): Response
    {
        $user = auth()->user()->id;

        if ($link->user_id !== $user) {
            abort(403);
        }

        return Inertia::render('links/edit', [
            'link' => [
                'id' => $link->id,
                'code' => $link->code,
                'url' => $link->url,
                'title' => $link->title,
                'is_active' => (bool) $link->is_active,
                'expires_at' => $link->expires_at,
                'has_password' => filled($link->password),
            ],
        ]);
    }

    public function update(Request $request, Links $link): RedirectResponse
    {
        $user = auth()->user()->id;

        if ($link->user_id !== $user) {
            abort(403);
        }

        $validated = $request->validate([
            'url' => ['required', 'url'],
            'title' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'expires_at' => ['nullable', 'date'],
            'password' => ['nullable', 'string', 'max:255'],
        ]);

        $attributes = [
            'url' => $validated['url'],
            'title' => $validated['title'] ?? null,
            'is_active' => $validated['is_active'],
            'expires_at' => $validated['expires_at'] ?? null,
        ];

        if (filled($validated['password'] ?? null)) {
            $attributes['password'] = Hash::make($validated['password']);
        }

        $link->update($attributes);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Enlace actualizado correctamente.',
        ]);

        return to_route('links.index');
    }

    public function destroy(Links $link): RedirectResponse
    {
        $user = auth()->user()->id;

        if ($link->user_id !== $user) {
            abort(403);
        }

        $link->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Enlace eliminado correctamente.',
        ]);

        return to_route('links.index');
    }

    public function redirect(string $code): RedirectResponse|Response
    {
        $link = Links::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        if ($link->expires_at?->isPast()) {
            abort(404);
        }

        if (filled($link->password)) {
            return Inertia::render('links/password', [
                'link' => [
                    'code' => $link->code,
                    'title' => $link->title,
                ],
            ]);
        }

        return redirect()->away($link->url);
    }

    public function verifyPassword(Request $request, string $code): RedirectResponse|Response
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'max:255'],
        ]);

        $link = Links::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        if ($link->expires_at?->isPast()) {
            abort(404);
        }

        if (!Hash::check($validated['password'], $link->password)) {
            throw ValidationException::withMessages([
                'password' => 'Contraseña incorrecta.',
            ]);
        }

        return Inertia::render('links/password', [
            'link' => [
                'code' => $link->code,
                'title' => $link->title,
            ],
            'redirectUrl' => $link->url,
        ]);
    }
}
