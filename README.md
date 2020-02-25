<p align="center"><a href="https://github.com/wavevision"><img alt="Wavevision s.r.o." src="https://wavevision.com/images/wavevision-logo.png" width="120" /></a></p>
<h1 align="center">Nette Webpack</h1>

[![Build Status](https://travis-ci.org/wavevision/nette-webpack.svg?branch=master)](https://travis-ci.org/wavevision/nette-webpack)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?label=phpstan)](https://github.com/phpstan/phpstan)
[![Coverage Status](https://coveralls.io/repos/github/wavevision/nette-webpack/badge.svg?branch=master)](https://coveralls.io/github/wavevision/nette-webpack?branch=master)

Webpack adapter for Nette framework consisting of:

- DI extension
- entry point chunks resolver
- UI components to render assets `<script>` and `<link>` tags
- webpack config helper to manage your setup consistently with `.neon` files

## Installation

Install the DI extension via [Composer](https://getcomposer.org)

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
