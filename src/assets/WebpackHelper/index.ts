import { parse, UrlWithParsedQuery } from 'url';
import { resolve } from 'path';

import WebpackManifestPlugin from 'webpack-manifest-plugin';

import Neon from '../Neon';
import {
  DEFAULT_DIST,
  DEFAULT_ENTRIES,
  DEFAULT_MANIFEST,
  DEFAULT_URL,
} from '../constants';

import { getManifestOptions } from './utils';
import { Entries, NeonConfig, Options } from './types';

class WebpackHelper {
  public constructor(options: Options) {
    this.options = options;
    this.neonConfig = this.options.neonPath ? this.parseNeonConfig() : {};
  }

  private readonly neonConfig: NeonConfig;

  private readonly options: Options;

  public readonly createManifestPlugin = (): WebpackManifestPlugin =>
    new WebpackManifestPlugin(
      getManifestOptions({
        ...(this.options.manifestOptions || {}),
        fileName: this.getManifest(),
        publicPath: '',
      }),
    );

  public readonly getDevServerPublicPath = (): string =>
    `${this.getDevServerUrl().href}${this.getDist()}/`;

  public readonly getDevServerUrl = (): UrlWithParsedQuery => {
    if (
      this.neonConfig.webpack &&
      this.neonConfig.webpack.devServer &&
      this.neonConfig.webpack.devServer.url
    ) {
      return parse(this.neonConfig.webpack.devServer.url, true);
    }
    return parse(DEFAULT_URL, true);
  };

  public readonly getDist = (): string => {
    if (this.neonConfig.webpack && this.neonConfig.webpack.dist) {
      return this.neonConfig.webpack.dist;
    }
    return DEFAULT_DIST;
  };

  public readonly getEntries = (): Entries => {
    if (this.neonConfig.webpack && this.neonConfig.webpack.entries) {
      return this.neonConfig.webpack.entries;
    }
    return DEFAULT_ENTRIES;
  };

  public readonly getEnabledEntries = (): string[] => {
    const enabled = [];
    const entries = this.getEntries();
    for (const entry in entries) {
      if (entries[entry] === true) enabled.push(entry);
    }
    return enabled;
  };

  public readonly getManifest = (): string => {
    if (this.neonConfig.webpack && this.neonConfig.webpack.manifest) {
      return this.neonConfig.webpack.manifest;
    }
    return DEFAULT_MANIFEST;
  };

  public readonly getOutputPath = (): string => {
    if (this.neonConfig.webpack && this.neonConfig.webpack.dir) {
      return Neon.replaceWwwDir(
        this.neonConfig.webpack.dir,
        this.options.wwwDir,
      );
    }
    return resolve(this.options.wwwDir, this.getDist());
  };

  public readonly parseNeonConfig = <T extends NeonConfig>(): T => {
    if (!this.options.neonPath) {
      throw new Error(
        "Unable to parse neon config without 'neonPath' option defined.",
      );
    }
    return Neon.decode<T>(this.options.neonPath);
  };
}

export default WebpackHelper;
