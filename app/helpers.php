<?php

if (!function_exists("replacePlaceholders")) {
    function replacePlaceholders(string $str, $substitutions)
    {
        if(!is_array($substitutions)) {
            $substitutions = [$substitutions];
        }

        foreach ($substitutions as $key => $value) {
            $str = str_replace("{{$key}}", $value, $str);
        }

        return $str;
    }
}