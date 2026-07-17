<?php

declare(strict_types=1);

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class SanitizeHtmlService
{
    private ?HTMLPurifier $purifier = null;

    public function clean(string $html): string
    {
        return $this->getPurifier()->purify($html);
    }

    private function getPurifier(): HTMLPurifier
    {
        if ($this->purifier === null) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', 'h1,h2,h3,h4,h5,h6,p[class|id],br,strong,em,ul,ol,li,a[href|title|target],img[src|alt|width|height],pre,code,blockquote,table,thead,tbody,tr,th,td,div[class|id],span[class|id],sup,sub,hr,dl,dt,dd,abbr');
            $config->set('Attr.AllowedFrameTargets', ['_blank', '_self']);
            $config->set('Attr.EnableID', true);
            $config->set('AutoFormat.RemoveEmpty', true);
            $config->set('Cache.DefinitionImpl', null);

            $this->purifier = new HTMLPurifier($config);
        }

        return $this->purifier;
    }
}
