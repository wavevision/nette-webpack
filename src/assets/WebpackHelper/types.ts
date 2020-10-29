import { Options as ManifestOptions } from 'webpack-manifest-plugin';

export type Entries = Record<string, boolean>;
export type ManifestEntries = Record<string, string[]>;
export { ManifestOptions };

export interface NeonConfig {
  readonly webpack?: {
    readonly devServer?: {
      readonly url?: string;
    };
    readonly dir?: string;
    readonly dist?: string;
    readonly entries?: Entries;
    readonly manifest?: string;
  };
}

export interface Options {
  readonly neonPath?: string;
  readonly wwwDir: string;
  readonly manifestOptions?: ManifestOptions;
}
