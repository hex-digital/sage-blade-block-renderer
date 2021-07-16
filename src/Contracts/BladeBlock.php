<?php

namespace BladeBlock\Contracts;

interface BladeBlock
{
    /**
     * Data to be passed to the rendered block view.
     *
     * @param array $block
     * @return array
     */
    public function with($block);
}
