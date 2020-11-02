import WebpackManifestPlugin from 'webpack-manifest-plugin';

import { getManifestOptions } from '../utils';

describe('WebpackHelper/utils', () => {
  describe('getManifestOptions', () => {
    it('creates ManifestPlugin options', () => {
      const options = getManifestOptions({ generate: seed => seed });
      const generate = options.generate as Required<
        WebpackManifestPlugin.Options
      >['generate'];
      expect(generate({}, [], {})).toEqual({ chunks: {} });
    });
  });
});
