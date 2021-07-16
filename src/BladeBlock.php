<?php

namespace BladeBlock;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use BladeBlock\Contracts\BladeBlock as BlockContract;
use BladeBlock\Concerns\InteractsWithBlade;

abstract class BladeBlock extends Composer implements BlockContract
{
    use InteractsWithBlade;

    /**
     * The block properties.
     *
     * @var array|object
     */
    public $block;

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
     * The block prefix.
     *
     * @var string
     */
    public $prefix = 'hex/';

    /**
     * The block name.
     *
     * @var string
     */
    public $name = '';

    /**
     * The block slug.
     *
     * @var string
     */
    public $slug = '';

    /**
     * The block view.
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
        if (empty($this->name)) {
            return;
        }

        if (empty($this->slug)) {
            $this->slug = Str::slug(Str::kebab($this->name));
        }

        if (empty($this->view)) {
            $this->view = Str::start($this->slug, 'blocks.');
        }

        $this->register(function () {
            register_block_type(Str::start($this->prefix, $this->slug), [
                'render_callback' => function ($block, $content = '', $preview = false, $post_id = 0) {
                    echo $this->render($block, $content, $preview, $post_id);
                },
            ]);
        });

        return $this;
    }

    /**
     * Render the Blade block.
     *
     * @param  array $block
     * @param  string $content
     * @param  bool $preview
     * @param  int $post_id
     * @return string
     */
    public function render($block, $content = '', $preview = false, $post_id = 0)
    {
        $this->block = (object) $block;
        $this->content = $content;
        $this->preview = $preview;

        $this->post = get_post($post_id);
        $this->post_id = $post_id;

        $this->classes = collect([
            'slug' => Str::start(
                Str::slug($this->slug),
                'wp-block-'
            ),
            'align' => ! empty($this->block->align) ?
                Str::start($this->block->align, 'align') :
                false,
            'align_text' => ! empty($this->supports['align_text']) ?
                Str::start($this->block->align_text, 'align-text-') :
                false,
            'align_content' => ! empty($this->supports['align_content']) ?
                Str::start($this->block->align_content, 'is-position-') :
                false,
            'classes' => $this->block->className ?? false,
        ])->filter()->implode(' ');

        return $this->view($this->view, ['block' => $this]);
    }
}
