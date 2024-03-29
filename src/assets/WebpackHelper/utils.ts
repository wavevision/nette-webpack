import { ManifestEntries, ManifestOptions } from './types';

export const formatManifestChunks = (
  entries: ManifestEntries,
  manifest: Record<string, unknown>,
): ManifestEntries => {
  const chunks: ManifestEntries = {};
  for (const asset in manifest) {
    const name = manifest[asset as keyof Record<string, unknown>] as string;
    for (const entry in entries) {
      if (!chunks[entry]) chunks[entry] = [];
      for (const entryAsset of entries[entry]) {
        if (name === entryAsset && !name.includes('.map')) {
          chunks[entry].push(asset);
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
    chunks: formatManifestChunks(entries, manifest as Record<string, unknown>),
  });
};

export const getManifestOptions = (
  options: ManifestOptions,
): ManifestOptions => {
  const generator = options.generate;
  let currentGenerator = generateManifest;
  if (typeof generator === 'function') {
    currentGenerator = (seed, files, entries) =>
      generator(generateManifest(seed, files, entries), files, entries);
  }
  return { ...options, generate: currentGenerator };
};
