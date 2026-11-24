<?php

namespace App\Http\Controllers;

use App\Models\Links;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class LinksAdminController extends Controller
{
    public function index(Request $request)
    {

        $links = Links::query()
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

        return Inertia::render('links/admin/index', [
            'appUrl' => rtrim(config('app.url'), '/'),
            'links' => $links,
        ]);

    }

    public function edit(Links $link): Response
    {
        return Inertia::render('links/admin/edit', [
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
            'user_id' => $request->user()->id,
            'code' => Str::random(6),
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

        return to_route('admin');
    }

    public function update(Request $request, Links $link): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'unique:links,code,'.$link->id],
            'url' => ['required', 'url'],
            'title' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'expires_at' => ['nullable', 'date'],
            'password' => ['nullable', 'string'],
        ]);

        if (filled($validated['password'] ?? null)) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $link->update($validated);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Enlace actualizado correctamente.',
        ]);

        return to_route('admin');
    }

    public function show(Links $link): Response
    {
        return Inertia::render('links/admin/show', [
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

    public function destroy(Links $link): RedirectResponse
    {
        $link->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Enlace eliminado correctamente.',
        ]);

        return to_route('admin');
    }
}
