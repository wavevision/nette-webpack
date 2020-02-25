import { resolve } from 'path';

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
    rules: [{ test: /\.ts$/, exclude: /node_modules/, loader: 'ts-loader' }],
  },
  output: {
    filename: '[name].js',
    path: helper.getOutputPath(),
  },
  plugins: [new CleanWebpackPlugin(), helper.createManifestPlugin()],
  resolve: {
    extensions: ['.js', '.ts'],
  },
};

export default config;
