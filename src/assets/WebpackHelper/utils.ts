import { ManifestEntries, ManifestOptions, Options } from './types';

export const formatManifestChunks = (
  entries: ManifestEntries,
  manifest: object,
): ManifestEntries => {
  const chunks: ManifestEntries = {};
  for (const asset in manifest) {
    const name = manifest[asset as keyof object] as string;
    for (const entry in entries) {
      for (const entryAsset of entries[entry]) {
        if (name === entryAsset && !name.includes('.map')) {
          if (!chunks[entry]) chunks[entry] = [];
          chunks[entry].push(name);
        }
      }
    }
  }
  return chunks;
};

export const generateManifest: ManifestOptions['generate'] = (
  seed,
  files,
  entries,
) => {
  const manifest = files.reduce(
    (manifest, { name, path }) => ({
      ...manifest,
      [name as string]: path,
    }),
    seed,
  );
  return Object.assign(manifest, {
    chunks: formatManifestChunks(entries, manifest),
  });
};

export const getManifestOptions = (options: Options): ManifestOptions => {
  const manifestOptions = options.manifestOptions || {};
  const generator = manifestOptions.generate;
  let currentGenerator = generateManifest;
  if (typeof generator === 'function') {
    currentGenerator = (seed, files, entries) =>
      generator(generateManifest(seed, files, entries), files, entries);
  }
  return { ...manifestOptions, generate: currentGenerator };
};
