# Sage: Blade Block Renderer 

![Latest Stable Version](https://img.shields.io/packagist/v/hex-digital/sage-blade-block-renderer.svg?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/hex-digital/sage-blade-block-renderer.svg?style=flat-square)
![Build Status](https://img.shields.io/github/workflow/status/hex-digital/sage-blade-block-renderer/Compatibility%20Checks?style=flat-square)

The Blade Block Renderer for Sage allows the easy registration of blade partials for the render function
of native Gutenberg blocks.  
Useful when you want to edit a block in React, but render with Blade.

## Features

- 🔥 Use all your blade components and PHP functions immediately in your native Gutenberg blocks. 
- 🔥 Instantly generate a working render_callback, powered by Blade with a native Sage 10 feel for passing view data.
- 🔥 All blocks support `InnerBlocks` content

## Requirements

- [Sage](https://github.com/roots/sage) >= 10.0

## Installation

Install via Composer:

```bash
$ composer require hex-digital/sage-blade-block-renderer
```

## Usage

### Create your native block

Create your Gutenberg block as you normally would, with one small change.

Instead of adding HTML to the `save` function of your block, instead define it as one of the following:

```js
// If you have InnerBlocks content:
save: function (props) {
  return <InnerBlocks.Content/>;
}

// If there is no InnerBlocks content:
save: function() {
  return null;
}
```

And that's it! When your block is saved, Gutenberg will save the settings to the database.
When it comes time to render your block, the settings will be given to the Blade Block Renderer,
and your blade template will be used, along with any attributes, classNames or Inner Blocks given.

If you prefer to use Blade for both the `edit` and `save` HTML, you would benefit from using 
[Log1x's ACF Composer package](https://github.com/log1x/acf-composer) instead. This similarly
provides Blade support for blocks, but utilises ACF's Blocks instead, which will be much more powerful.

### Rendering a Block with a blade-powered render callback

To create your first rendered block, start by running the following generator command from your theme directory:

```bash
$ wp acorn blade-block Example
```

This will create `app/BladeBlocks/Example.php` which is where you will create and manage your first blade block:

```php
<?php

namespace Example;

use BladeBlock\BladeBlock;

class Example extends BladeBlock
{
    /**
     * The block slug. Should match the slug given to your registered block.
     *
     * @var string
     */
    public $slug = 'example';

    /**
     * Data to be passed to the block before rendering.
     *
     * @param BladeBlock $block
     * @return array
     */
    public function with($block)
    {
        return [
            'data' => 'dummy data',
        ];
    }
}
```

You'll see it has a familiar `with()` function for passing data to your block. However,
it is also given the block object as the first parameter.

This allows you to get any attribute, setting or content from the block, as required.

For example, `$block->content` will give the Inner Blocks content, and `$block->image_id` would give
the value of an attribute called `image_id` that was defined on the block in JavaScript.

A `View` is also generated in the `resources/views/blocks` directory:

```blade
<div class="{{ $block->classes }}">
  @if ($data)
    <p>{!! $data !!}
  @else
    <p>No data found!</p>
  @endif

  @if ($content)
    {!! $content !!}
  @endif
</div>
```

The `$content` variable will contain the HTML for any InnerBlocks added in the editor.  
The `$blockClass` variable will contain the base class for the block, for use with BEM class naming.  
This is in the form `"wp-block-$prefix-$slug"` E.G. `wp-block-hex-page-header`.  
These can then be outputted as required into the blade partial.

All block data is available on the `$block` object, which is the same as that passed to the
BladeBlock `with()` function. All data returned from the `with()` function is also available.

#### Block Preview View

In the view file, you can use `$block->preview` for conditionally modifying your block when shown in the editor.

You can also load a different blade partial by duplicating your existing view and prefixing it with `preview-` 
(e.g. `preview-example.blade.php`).

However, if you're using the blade partial for both the `edit` and `save` HTML, you would benefit from using
[Log1x's ACF Composer package](https://github.com/log1x/acf-composer) instead. This similarly
provides Blade support for blocks, but utilises ACF's Blocks instead, which will be much more powerful.

### Modifying your Block

Your Block has a few options available to it to modify. Add these as member variables to your
generated block class to define and use them.

Your block's class will default to `"wp-block-$prefix-$slug"`, where the prefix has the trailing slash trimmed.
This block class is available in the view as `$blockClass` for easy use with BEM class naming.

```php
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
```

## Bug Reports

If you discover a bug in Sage Blade Block Renderer, please [open an issue](https://github.com/hex-digital/sage-blade-block-renderer/issues).

## Contributing

Contributing, whether through PRs, reporting an issue, or suggesting an idea, is encouraged and extremely appreciated.

## License

We provide the Sage Blade Block Renderer under the [MIT License](LICENSE.md).
