<?php

namespace App\Support;

class Helpers
{
    public static function cidr_match($ip, $range)
    {
        list ($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
        return ($ip & $mask) == $subnet;
    }

    public static function isAcceptableNetwork($cidr)
    {
        $current_ip = request()->ip();

        if(self::cidr_match($current_ip, $cidr)) {
            return true;
        } else {
            return false;
        }
    }

    public static function mix2($path, $manifestDirectory = '')
    {
        static $manifest;

        if (! starts_with($path, '/')) {
            $path = "/{$path}";
        }
        if ($manifestDirectory && ! starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }
        if (file_exists(public_path($manifestDirectory.'/hot'))) {
            return new HtmlString("//localhost:8080{$path}");
        }
        if (! $manifest) {
            if (! file_exists($manifestPath = public_path($manifestDirectory.'/mix-manifest.json'))) {
                throw new Exception('The Mix manifest does not exist.');
            }
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }
        if (! array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }
    }
}
