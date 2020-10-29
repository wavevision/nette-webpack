import webpack from 'webpack';

import config from '../../../examples/webpack.config';

// eslint-disable-next-line jest/no-export
export default (): void => {
  it('compiles webpack assets', () =>
    new Promise(done => {
      const compiler = webpack(config);
      compiler.run((err, stats) => {
        expect(err).toBeNull();
        expect(stats.toJson().assets).toHaveLength(6);
        expect(stats.toString('minimal')).toContain('3 modules');
        done();
      });
    }));
};
