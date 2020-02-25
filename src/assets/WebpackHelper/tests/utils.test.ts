import { getManifestOptions } from '../utils';

describe('WebpackHelper/utils', () => {
  describe('getManifestOptions', () => {
    it('creates ManifestPlugin options', () => {
      const options = getManifestOptions({ generate: seed => seed });
      const generate = options.generate as Function;
      expect(generate({}, [], {})).toEqual({ chunks: {} });
    });
  });
});
