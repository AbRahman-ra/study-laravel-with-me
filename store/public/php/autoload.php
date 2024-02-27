<?php

function load_class($className)
{
    include __DIR__.DIRECTORY_SEPARATOR.$className.'.php';
}

spl_autoload_register('load_class');
