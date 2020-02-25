import { existsSync, readFileSync } from 'fs';
import { resolve } from 'path';

import { helper } from '../../../examples/webpack.config';

const ENTRY_ASSETS = ['entry.css', 'entry.js'];
const MANIFEST_PATH = resolve(helper.getOutputPath(), 'manifest.json');

// eslint-disable-next-line jest/no-export
export default (): void => {
  it('created dist with entry and manifest', () => {
    for (const asset of ENTRY_ASSETS) {
      expect(existsSync(resolve(helper.getOutputPath(), asset))).toBe(true);
    }
    expect(existsSync(MANIFEST_PATH)).toBe(true);
    const manifest = JSON.parse(readFileSync(MANIFEST_PATH).toString());
    expect(manifest.chunks).toEqual({ entry: ENTRY_ASSETS });
  });
};
