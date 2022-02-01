<?php

namespace Usanzadunje\Scaffold\Helpers;

use Illuminate\Support\HtmlString;

class ViteAssets
{
    /**
     * @return HtmlString
     */
    public static function generate(): HtmlString {
        $devServerIsRunning = false;

        if(app()->environment('local')) {
            $devServerIsRunning = true;
        }

        if($devServerIsRunning) {
            return new HtmlString(<<<HTML
            <script type="module" src="http://localhost:3001/@vite/client"></script>
            <script type="module" src="http://localhost:3001/resources/js/app.js"></script>
        HTML
            );
        }

        $manifest = json_decode(file_get_contents(public_path('dist/manifest.json')), true);

        return new HtmlString(<<<HTML
        <script type="module" src="/dist/{$manifest['resources/js/app.js']['file']}"></script>
        <link rel="stylesheet" href="/dist/{$manifest['resources/js/app.js']['css'][0]}">
    HTML
        );
    }
}