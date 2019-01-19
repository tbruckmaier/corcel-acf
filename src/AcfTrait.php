<?php

namespace Tbruckmaier\Corcelacf;

/**
 * This class can be used on any Corcel model to add the getAcfAttribute()
 * method
 */
trait AcfTrait
{
    public function getAcfAttribute()
    {
        return new Acf($this);
    }
}
