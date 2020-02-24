import { ManifestEntries, ManifestOptions, Options } from './types';

export const invertManifest = (manifest: object): object => {
  const output: Record<string, unknown[]> = {};
  Object.keys(manifest).forEach(key => {
    const value = manifest[key as keyof object];
    output[value] = output[value] || [];
    output[value].push(key);
  });
  return output;
};

export const formatManifestChunks = (
  entries: ManifestEntries,
  manifest: object,
): ManifestEntries => {
  const chunks: ManifestEntries = {};
  const assets = invertManifest(manifest);
  for (const entry in entries) {
    if (!chunks[entry]) chunks[entry] = [];
    for (const asset of entries[entry]) {
      if (asset in assets && !asset.includes('.map')) {
        chunks[entry].push(assets[asset as keyof object]);
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
