<?php

namespace BladeBlock\Contracts;

interface BladeBlock
{
    /**
     * Data to be passed to the rendered block view.
     *
     * @return array
     */
    public function with();
}
