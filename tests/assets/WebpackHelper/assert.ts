import { existsSync, readFileSync } from 'fs';
import { resolve } from 'path';

import { helper } from '../../../examples/webpack.config';

// eslint-disable-next-line jest/no-export
export default (): void => {
  it('created dist with entry and manifest', () => {
    const entry = 'entry.js';
    const manifestPath = resolve(helper.getOutputPath(), 'manifest.json');
    expect(existsSync(resolve(helper.getOutputPath(), entry))).toBe(true);
    expect(existsSync(manifestPath)).toBe(true);
    const manifest = JSON.parse(readFileSync(manifestPath).toString());
    expect(manifest.chunks).toEqual({ entry: [entry] });
  });
};
