# Sage: Blade Block Renderer 

![Latest Stable Version](https://img.shields.io/packagist/v/hex-digital/sage-blade-block-renderer.svg?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/hex-digital/sage-blade-block-renderer.svg?style=flat-square)
![Build Status](https://img.shields.io/github/workflow/status/hex-digital/sage-blade-block-renderer/Compatibility%20Checks?style=flat-square)

The Blade Block Renderer for Sage allows the easy registration of blade partials for the render function
of native Gutenberg blocks. Useful when you want to edit a block in React, but render with Blade.

![Screenshot](https://i.imgur.com/7e7w3U9.png)

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

### Rendering a Block with a blade-powered render callback

To create your first rendered block, start by running the following generator command from your theme directory:

```bash
$ wp acorn blade-block:block Example
```

This will create `app/BladeBlocks/Example.php` which is where you will create and manage your first blade block.

Take a look at it once created. You'll see it has a familiar `with()` function for passing data to your block. 

An `View` is also generated in the `resources/views/blocks` directory. Feel free to check that now too.

#### Block Preview View

While `$block->preview` is an option for conditionally modifying your block when shown in the editor, you may also render your block using a seperate view.

Simply duplicate your existing view prefixing it with `preview-` (e.g. `preview-example.blade.php`).

## Bug Reports

If you discover a bug in Sage Blade Block Renderer, please [open an issue](https://github.com/hex-digital/sage-blade-block-renderer/issues).

## Contributing

Contributing, whether through PRs, reporting an issue, or suggesting an idea, is encouraged and extremely appreciated.

## License

We provide the Sage Blade Block Renderer under the [MIT License](LICENSE.md).
