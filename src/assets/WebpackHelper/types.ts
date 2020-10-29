import { Options as ManifestOptions } from 'webpack-manifest-plugin';

export type Entries = Record<string, boolean>;
export type ManifestEntries = Record<string, string[]>;
export type NeonType = Record<string, unknown>;
export { ManifestOptions };

export interface NeonConfig extends NeonType {
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
