import { resolve } from 'path';

import WebpackHelper from '../index';
import {
  DEFAULT_DIST,
  DEFAULT_ENTRIES,
  DEFAULT_MANIFEST,
} from '../../constants';

const ROOT_DIR = resolve(__dirname, '..', '..', '..', '..');
const WWW_DIR = resolve(ROOT_DIR, 'examples', 'www');
const NEON_PATH = resolve(ROOT_DIR, 'examples', 'config', 'common.neon');

describe('WebpackHelper unit test', () => {
  describe('neonPath is defined', () => {
    const helper = new WebpackHelper({ wwwDir: WWW_DIR, neonPath: NEON_PATH });
    describe('getDevServerUrl', () => {
      it('returns parsed url', () => {
        expect(helper.getDevServerUrl().href).toEqual('http://localhost:9006/');
      });
    });
    describe('getDist', () => {
      it('returns dist name', () => {
        expect(helper.getDist()).toEqual('dist');
      });
    });
  });
  describe('neonPath is undefined', () => {
    const helper = new WebpackHelper({ wwwDir: WWW_DIR });
    describe('getDevServerUrl', () => {
      it('returns default url', () => {
        expect(helper.getDevServerUrl().href).toEqual('http://localhost:9006/');
      });
    });
    describe('getDist', () => {
      it('returns default dist name', () => {
        expect(helper.getDist()).toEqual(DEFAULT_DIST);
      });
    });
    describe('getEntries', () => {
      it('returns default entries', () => {
        expect(helper.getEntries()).toEqual(DEFAULT_ENTRIES);
      });
    });
    describe('getManifest', () => {
      it('returns default manifest', () => {
        expect(helper.getManifest()).toEqual(DEFAULT_MANIFEST);
      });
    });
    describe('getOutputPath', () => {
      it('returns default output path', () => {
        expect(helper.getOutputPath()).toEqual(resolve(WWW_DIR, DEFAULT_DIST));
      });
    });
    describe('parseNeonConfig', () => {
      it('throws error', () => {
        expect(() => {
          helper.parseNeonConfig();
        }).toThrow(
          "Unable to parse neon config without 'neonPath' option defined.",
        );
      });
    });
  });
});
