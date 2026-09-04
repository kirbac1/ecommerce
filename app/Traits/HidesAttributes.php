<?php

namespace App\Traits;

trait HidesAttributes
{
    public function hideAttributes($attribs)
    {
        foreach($attribs as $attrib) {
            array_push($this->hidden, $attrib);
        }
    }
}
