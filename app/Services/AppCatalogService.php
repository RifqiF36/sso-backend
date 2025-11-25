<?php

namespace App\Services;

use Illuminate\Support\Str;

class AppCatalogService
{
    /**
     * Build list of accessible apps for the provided role slugs.
     *
     * @param  array<int, string|null>  $roleSlugs
     * @return array<int, array<string, mixed>>
     */
    public static function forRoleSlugs(array $roleSlugs): array
    {
        $normalizedRoleSlugs = collect($roleSlugs)
            ->map(fn ($role) => self::normalizeRoleSlug($role))
            ->filter()
            ->unique()
            ->values();

        $apps = config('role_apps.apps', config('role_apps', []));

        return collect($apps)
            ->map(function ($appConfig, $code) use ($normalizedRoleSlugs) {
                $baseUrl = $appConfig['url'] ?? '#';
                $rawAllowedRoles = collect($appConfig['roles'] ?? []);
                $allowAll = $rawAllowedRoles->contains('*');
                $allowedRoles = $rawAllowedRoles
                    ->reject(fn ($role) => $role === '*')
                    ->map(fn ($role) => self::normalizeRoleSlug($role))
                    ->filter()
                    ->values();

                if (
                    !$allowAll
                    && $allowedRoles->isNotEmpty()
                    && $allowedRoles->intersect($normalizedRoleSlugs)->isEmpty()
                ) {
                    return null;
                }

                $roleBaseUrls = collect($appConfig['role_urls'] ?? [])
                    ->mapWithKeys(function ($url, $role) {
                        $slug = self::normalizeRoleSlug($role);
                        $normalizedUrl = self::normalizeBaseUrl($url);
                        return $slug && $normalizedUrl ? [$slug => $normalizedUrl] : [];
                    });

                $roleRoutes = collect($appConfig['role_routes'] ?? [])
                    ->mapWithKeys(function ($route, $role) {
                        $slug = self::normalizeRoleSlug($role);
                        $normalizedRoute = self::normalizeRoute($route);
                        return $slug ? [$slug => $normalizedRoute] : [];
                    });

                $defaultRoute = self::normalizeRoute($appConfig['default_route'] ?? '/');
                $resolvedRoute = $roleRoutes
                    ->filter(fn ($route) => !is_null($route))
                    ->first(function ($route, $roleSlug) use ($normalizedRoleSlugs) {
                        return $normalizedRoleSlugs->contains($roleSlug);
                    }, $defaultRoute);

                $resolvedBaseUrl = $roleBaseUrls
                    ->filter()
                    ->first(function ($url, $roleSlug) use ($normalizedRoleSlugs) {
                        return $normalizedRoleSlugs->contains($roleSlug);
                    }, $baseUrl);

                $entryUrl = self::buildEntryUrl($resolvedBaseUrl, $resolvedRoute);

                return [
                    'code' => $code,
                    'name' => $appConfig['name'] ?? ucfirst($code),
                    'url' => $entryUrl,
                    'login_url' => $baseUrl,
                    'description' => $appConfig['description'] ?? null,
                    'icon' => $appConfig['icon'] ?? null,
                    'default_route' => $resolvedRoute,
                    'routes' => [
                        'default' => $defaultRoute,
                        'per_role' => $roleRoutes->all(),
                    ],
                    'allowed_roles' => $allowedRoles->all(),
                    'role_base_urls' => $roleBaseUrls->all(),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected static function normalizeRoleSlug($role): ?string
    {
        if ($role === null) {
            return null;
        }

        $role = trim((string) $role);
        if ($role === '') {
            return null;
        }

        return Str::slug($role, '_');
    }

    protected static function normalizeRoute($route): string
    {
        if ($route === null) {
            return '/';
        }

        $route = trim((string) $route);
        if ($route === '') {
            return '/';
        }

        return Str::startsWith($route, '/') ? $route : '/' . ltrim($route, '/');
    }

    protected static function normalizeBaseUrl($url): ?string
    {
        if ($url === null) {
            return null;
        }

        $normalized = trim((string) $url);
        if ($normalized === '') {
            return null;
        }

        return $normalized;
    }

    protected static function buildEntryUrl(string $baseUrl, string $route): string
    {
        if ($baseUrl === '#') {
            return '#';
        }

        if (str_contains($baseUrl, '{redirect}')) {
            return str_replace('{redirect}', ltrim($route, '/'), $baseUrl);
        }

        $glue = str_contains($baseUrl, '?') ? '&' : '?';

        return rtrim($baseUrl, '&?') . $glue . 'redirect=' . urlencode($route);
    }
}
