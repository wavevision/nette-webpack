import assert from './assert';
import compile from './compile';

jest.setTimeout(10000);

describe('WebpackHelper functional test', () => {
  compile();
  assert();
});
