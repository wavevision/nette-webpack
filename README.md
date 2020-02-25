<p align="center"><a href="https://github.com/wavevision"><img alt="Wavevision s.r.o." src="https://wavevision.com/images/wavevision-logo.png" width="120" /></a></p>
<h1 align="center">Nette Webpack</h1>

[![Build Status](https://travis-ci.org/wavevision/nette-webpack.svg?branch=master)](https://travis-ci.org/wavevision/nette-webpack)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?label=phpstan)](https://github.com/phpstan/phpstan)
[![Coverage Status](https://coveralls.io/repos/github/wavevision/nette-webpack/badge.svg?branch=master)](https://coveralls.io/github/wavevision/nette-webpack?branch=master)
[![Release](https://img.shields.io/github/v/tag/wavevision/nette-webpack?label=version&sort=semver)](https://github.com/wavevision/nette-wepack/releases)

Webpack adapter for Nette framework consisting of:

- [DI extension](#di-extension)
- entry point chunks resolver **(uses webpack `manifest.json`)**
- UI components to render assets `<script>` and `<link>` tags
- [webpack config helper](#webpack-helper) to manage your setup consistently with `neon` files

## Installation

Install the DI extension via [Composer](https://getcomposer.org).

```bash
composer require wavevision/nette-webpack
```

The webpack helper can be installed via [Yarn](https://yarnpkg.com)

```bash
yarn add --dev @wavevision/nette-webpack
```

or [npm](https://npmjs.com)

```bash
npm install --save-dev @wavevision/nette-webpack
```

## Usage

### DI extension

Register DI extension in your app config.

```neon
extensions:
    webpack: Wavevision\NetteWebpack\DI\Extension(%debugMode%, %consoleMode%)
```

You can configure the extension as follows _(default values)_.

```neon
webpack:
    devServer:
        enabled: %debugMode%
        url: http://localhost:9006
    dir: %wwwDir%/dist
    dist: dist
    entries: []
    manifest: manifest.json
```

**The `entries` param specifies your webpack entry points. See [example config](./examples/config/common.neon) for further usage.**

Then, setup entry chunks.

```php
use Nette\Application\UI\Presenter;
use Wavevision\NetteWebpack\InjectResolveEntryChunks;
use Wavevision\NetteWebpack\UI\Components\Assets\AssetsComponent;

final class AppPresenter extends Presenter
{

    use AssetsComponent;
    use InjectResolveEntryChunks;

    public function actionDefault(): void
    {
        $this
            ->getAssetsComponent()
            ->setChunks($this->resolveEntryChunks->process('entry'));
    }
}
```

> **Note:** Entry chunks are resolved based on webpack `manifest.json`. You can also
> set chunks manually and/or separately with `setScripts` and `setStyles` methods.

Finally, render `assets` in your layout.

```latte
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>Wavevision Nette Webpack</title>
	{control assets:styles}
</head>
<body>
{include content}
{control assets:scripts}
</body>
</html>
```

Should you need it, you can inject and use following services to further customize your setup:

- [`WebpackParameters`](./src/NetteWebpack/WebpackParameters.php) – provides basic parameters to work with the extension
- [`FormatChunkName`](./src/NetteWebpack/FormatChunkName.php) – formats asset chunk names for specific content types and resolves their URL

### Webpack helper

This simple utility will help you to manage your project setup and webpack configs consistently. It will also provide you
with pre-configured [webpack-manifest-plugin](https://github.com/danethurber/webpack-manifest-plugin) to generate `manifest.json`
with extra `chunks` property that is used to dynamically resolve entry chunks in your application.

```typescript
import { WebpackHelper } from '@wavevision/nette-webpack';
```

The helper constructor accepts following arguments:

- **`neonPath?: string`** – path to a `neon` in which `webpack` is configured (if not provided, default values will be used)
- **`wwwDir: string`** – mandatory path to application `wwwDir`
- **`manifestOptions?: WebpackManifestPlugin.Options`** – if you need to customize manifest plugin setup, you can do it here

The returned class exposes following methods:

- **`createManifestPlugin(): WebpackManifestPlugin`** – creates manifest plugin instance
- **`getDevServerPublicPath(): string`** – returns resolved `webpack-dev-server` URL with `dist` included in path
- **`getDevServerUrl(): UrlWithParsedQuery`** – returns `webpack-dev-server` parsed URL object
- **`getDist(): string`** – returns `dist` parameter
- **`getEntries(): Record<string, boolean>`** – returns records of configured webpack entries
- **`getEnabledEntries(): string[]`** – returns list of webpack entries that have `true` configured
- **`getManifest(): string`** – returns webpack manifest file name
- **`getOutputPath(): string`** – returns resolved path to webpack output directory
- **`parseNeonConfig<T extends NeonConfig>(): T`** – returns parsed `neon` config (throws error if `neonPath` is not defined)

> **Note:** You can also import `Neon` helper if you want to parse and work with more `neon` files.

See [example webpack config](./examples/webpack.config.ts) to see it all in action.

## Credits

Many️ 🙏 to [Jiří Pudil](https://github.com/jiripudil) for his [WebpackNetteAdapter](https://github.com/o2ps/WebpackNetteAdapter) which we used in our projects and served as an inspiration for this library.
