const manifest = require('./client-manifest.json');
const bundle   = require('./server-bundle.json');
const runtime  = require('./server.js');

const context = { url: process.env.URI };

const { createBundleRenderer } = require('vue-server-renderer');

const renderer = createBundleRenderer(bundle, {
  runInNewContext: false,
  clientManifest: manifest,

  template: FROM_PHP.template
})

renderer.renderToString({ url: FROM_PHP.url })
  .then(html => print(html))
  .catch(err => var_dump(err))
