import { resolve } from 'path';

import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import { CleanWebpackPlugin } from 'clean-webpack-plugin';
import { Configuration, Entry } from 'webpack';

import { WebpackHelper } from '../src/assets';

export const helper = new WebpackHelper({
  neonPath: resolve(__dirname, 'config', 'common.neon'),
  wwwDir: resolve(__dirname, 'www'),
});

const makeEntries = (): Entry => {
  const entries: Entry = {};
  for (const entry of helper.getEnabledEntries()) {
    entries[entry] = resolve(__dirname, 'assets', `${entry}.ts`);
  }
  return entries;
};

const config: Configuration = {
  entry: makeEntries(),
  devtool: 'source-map',
  mode: 'production',
  module: {
    rules: [
      { test: /\.css$/, use: [MiniCssExtractPlugin.loader, 'css-loader'] },
      {
        test: /\.png$/,
        type: 'asset/resource',
        generator: { filename: 'images/[name][ext]?[fullhash]' },
      },
      { test: /\.ts$/, exclude: /node_modules/, loader: 'ts-loader' },
    ],
  },
  output: {
    filename: '[name].js?[fullhash]',
    path: helper.getOutputPath(),
  },
  plugins: [
    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({ filename: '[name].css?[fullhash]' }),
    helper.createManifestPlugin(),
  ],
  resolve: {
    extensions: ['.js', '.ts'],
  },
};

export default config;
