<?php

namespace App\Services;

class MenuService
{
    /**
     * Get menu configuration based on user role
     *
     * @param string|null $role
     * @param string|null $token
     * @return array
     */
    public static function getMenuByRole(?string $role, ?string $token = null): array
    {
        $roleApps = config('role_apps.apps', []);
        $menuMapping = config('menu.role_menu_mapping', []);
        
        // Normalize role name
        $normalizedRole = self::normalizeRole($role);
        
        // Get allowed apps for this role
        $allowedApps = $menuMapping[$normalizedRole] ?? [];
        
        $menu = [];
        
        foreach ($allowedApps as $appKey) {
            if (isset($roleApps[$appKey])) {
                $app = $roleApps[$appKey];
                $url = self::getRoleUrl($app, $normalizedRole);
                
                // Replace {token} placeholder with actual token
                if ($token) {
                    $url = str_replace('{token}', $token, $url);
                }
                
                $menu[] = [
                    'name' => $app['name'],
                    'url' => $url,
                    'logo' => $app['icon'] ?? '',
                    'description' => $app['description'] ?? '',
                ];
            }
        }
        
        return $menu;
    }

    /**
     * Normalize role name to match mapping keys
     *
     * @param string|null $role
     * @return string
     */
    private static function normalizeRole(?string $role): string
    {
        if (!$role) {
            return 'staff';
        }

        // Convert to lowercase and replace spaces with underscores
        $normalized = strtolower(str_replace(' ', '_', $role));
        
        // Get role aliases from config
        $roleMap = config('menu.role_aliases', []);
        
        return $roleMap[$normalized] ?? $normalized;
    }

    /**
     * Get URL for specific role from app configuration
     *
     * @param array $app
     * @param string $role
     * @return string
     */
    private static function getRoleUrl(array $app, string $role): string
    {
        // Check if role_urls exists and has URL for this role
        if (isset($app['role_urls'][$role])) {
            return $app['role_urls'][$role];
        }
        
        // Fallback to default URL
        return $app['url'] ?? '#';
    }
}
