// Ensure Vite includes brand images in the build manifest
// and makes them available during dev server as well.
// Force Vite to include all brand assets under resources/images/root
// and generate manifest entries for each one.
// Vite 7: use `query: '?url'` + `import: 'default'` (replaces deprecated `as: 'url'`)
const brandAssets = import.meta.glob('../images/root/*', { eager: true, import: 'default', query: '?url' });

if (typeof window !== 'undefined') {
    window.__brandAssets = brandAssets;
}

export {};
