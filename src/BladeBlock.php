<?php

namespace BladeBlock;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use BladeBlock\Contracts\BladeBlock as BlockContract;

use function Roots\view;

abstract class BladeBlock extends Composer implements BlockContract
{
    /**
     * The block attributes.
     *
     * @var array|object
     */
    public $attributes;

    /**
     * The block content.
     *
     * @var string
     */
    public $content;

    /**
     * The current post ID.
     *
     * @param int
     */
    public $post;

    /**
     * The block classes.
     *
     * @param string
     */
    public $classes;

    /**
     * The block prefix. Should match the prefix given to your block
     *
     * @var string
     */
    public $prefix = 'hex/';

    /**
     * The block slug. Should match the slug given to your registered block.
     *
     * @var string
     */
    public $slug = '';

    /**
     * The block view. Same format as given to the blade `include()` function. A dot-separated path where the root is `resources/views`.
     * If left blank, defaults to `'blocks.' . $this->slug`.
     *
     * @var string
     */
    public $view;

    /**
     * Compose the render callback and register it with the block editor.
     *
     * @return void|self
     */
    public function compose()
    {
        if (empty($this->slug)) {
            return;
        }

        if (empty($this->view)) {
            $this->view = Str::start($this->slug, 'blocks.');
        }

        $this->register(function () {
            register_block_type(Str::start($this->slug, $this->prefix), [
                'render_callback' => function ($attributes, $content = '', $preview = false, $post_id = 0) {
                    return $this->render($attributes, $content, $preview, $post_id);
                },
            ]);
        });

        return $this;
    }

    /**
     * Render the Blade block.
     *
     * @param  array $attributes
     * @param  string $content
     * @param  bool $preview
     * @param  int $post_id
     * @return string
     */
    public function render($attributes, $content = '', $preview = false, $post_id = 0)
    {
        $this->attributes = (object) $attributes;
        $this->content = $content;
        $this->preview = $preview;

        $this->post = get_post($post_id);
        $this->post_id = $post_id;

        $trimmedPrefix = rtrim($this->prefix, '/');

        $blockClass = Str::start(Str::slug($this->slug), 'wp-block-' . $trimmedPrefix . '-');

        $this->classes = collect([
            'slug' => Str::start(
                Str::slug($this->slug),
                'wp-block-' . $trimmedPrefix . '-'
            ),
            'align' => ! empty($this->attributes->align) ?
                Str::start($this->attributes->align, 'align') :
                false,
            'align_text' => ! empty($this->supports['align_text']) ?
                Str::start($this->attributes->align_text, 'align-text-') :
                false,
            'align_content' => ! empty($this->supports['align_content']) ?
                Str::start($this->attributes->align_content, 'is-position-') :
                false,
            'classes' => $this->attributes->className ?? false,
        ])->filter()->implode(' ');

        return view(
            $this->view,
            ['block' => $this, 'blockClass' => $blockClass, 'content' => $this->content],
            $this->with($this)
        )->render();
    }
}
