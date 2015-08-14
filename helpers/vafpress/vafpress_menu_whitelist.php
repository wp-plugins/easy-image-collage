<?php

function eic_vp_dep_boolean_inverse($value)
{
    $args   = func_get_args();
    $result = true;
    foreach ($args as $val)
    {
        $result = ($result and !empty($val));
    }
    return !$result;
}

VP_Security::instance()->whitelist_function('eic_vp_dep_boolean_inverse');