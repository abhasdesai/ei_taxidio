<?php

function cdn($url = null)
{
    $url = (string) $url;
    if(empty($url))
    {
        throw new Exception('URL missing');
    }

    $pattern = '|^http[s]{0,1}://|i';
    if(preg_match($pattern, $url))
    {
        throw new Exception('Invalid URL. ' .
            'Use: /image.jpeg instead of full URI: ' .
            'http://domain.com/image.jpeg.'
        );
    }

    $pattern = '|^/|';
    if(!preg_match($pattern, $url))
    {
        $url = '/' . $url;
    }

    $currentInstance    =& get_instance();
    $cdn_enabled        = $currentInstance->config->item('cdn_enabled');
    $cdn_domain         = $currentInstance->config->item('cdn_domain');
    $cdn_protocol       = $currentInstance->config->item('cdn_protocol');

    if(empty($cdn_enabled))
    {
        return $url;
    }
    else
    {
        return $cdn_protocol . '://' . $cdn_domain . $url;
    }
}
