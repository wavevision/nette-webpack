import { readFileSync } from 'fs';

// eslint-disable-next-line
// @ts-ignore
import neon from 'neon-js';

const decode = <T extends object = {}>(path: string): T =>
  neon.decode(readFileSync(path).toString()).toObject(true) as T;

const replaceParam = (param: string, needle: string, replace: string): string =>
  param.replace(needle, replace);

const replaceWwwDir = (param: string, wwwDir: string): string =>
  replaceParam(param, '%wwwDir%', wwwDir);

export default { decode, replaceParam, replaceWwwDir };
