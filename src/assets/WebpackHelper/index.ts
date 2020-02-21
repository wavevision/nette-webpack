import WebpackManifestPlugin from 'webpack-manifest-plugin';

import { getManifestOptions } from './utils';
import { Options } from './types';

class WebpackHelper {
  public constructor(options: Options) {
    this.options = options;
  }

  private readonly options: Options;

  public readonly createManifestPlugin = (): WebpackManifestPlugin =>
    new WebpackManifestPlugin(getManifestOptions(this.options));
}

export default WebpackHelper;
