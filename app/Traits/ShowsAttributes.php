<?php

namespace App\Traits;

trait ShowsAttributes
{
    public function showsAttributes($attribs)
    {
        foreach($attribs as $attrib) {
            try {
                unset($attrib);
            } catch (\Exception $e) {}
        }
    }
}
