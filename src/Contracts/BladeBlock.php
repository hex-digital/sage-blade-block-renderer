<?php

namespace BladeBlock\Contracts;

interface BladeBlock
{
    /**
     * Data to be passed to the rendered block view.
     *
     * @param BladeBlock $block
     * @return array
     */
    public function with($block);
}
