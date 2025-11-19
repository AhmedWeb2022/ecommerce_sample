<?php

use Illuminate\Support\Facades\Cache;

function clearCache()
{
    return Cache::flush();
}

function getCache($key)
{
    return Cache::get($key);
}

function setCache($key, $value, $minutes = 60)
{
    return Cache::put($key, $value, $minutes);
}

function forgetCache($key)
{
    return Cache::forget($key);
}


function getCacheTags($tags)
{
    return Cache::tags($tags);
}

function clearCacheTags($tags)
{
    return Cache::tags($tags)->flush();
}

function getCacheTag($tags, $key)
{
    return Cache::tags($tags)->get($key);
}

function setCacheTag($tags, $key, $value, $minutes = 60)
{
    return Cache::tags($tags)->put($key, $value, $minutes);
}

function forgetCacheTag($tags, $key)
{
    return Cache::tags($tags)->forget($key);
}
