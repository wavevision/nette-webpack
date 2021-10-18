import webpack from 'webpack';

import config from '../../../examples/webpack.config';

export default (): void => {
  it('compiles webpack assets', () =>
    new Promise<void>(done => {
      const compiler = webpack(config);
      compiler.run((err, stats) => {
        expect(err).toBeNull();
        if (stats) {
          expect(stats.toJson().assets).toHaveLength(4);
          expect(stats.toString('minimal')).toContain('4 assets');
        }
        done();
      });
    }));
};
