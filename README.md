# vue-ssr-v8js-starterkit
A minimal example about using Vue.js SSR with PHP as a server side technology (no node binary required)

### Requirements

First get yourself up and running with php and v8js. Assuming macOS :

1. php : `brew install php72`
2. v8js : https://github.com/phpv8/v8js/issues/380#issuecomment-502695765

You may test your config by searching for v8js extension in yout phpinfo by running :

```
echo '<?php phpinfo(); ?>' > phpinfo.php
php -f phpinfo.php | grep v8js
```

### Runtime

First, you need to compile the frontend (This task will usually be done by your CI)
For our demo, we'll keep it extra simple.

```
cd vue-ssr-starterkit
yarn install && yarn build
```

Then, you'll install the server-side dependencies and run the server.

```
composer install
php -S localhost:10004 router.php
```

Compared to other solutions, this uses the most advanced Vue.js bundler to inject properly all the prefetch directives (link tags), along with client side state rehydration.

This is made possible after few polyfills which were required for the bundler to work. All non-v8js injected things are available in `./renderer/node`. As for now, the only limitation stands in the fidelity in which `vm.Script` is implemented. I'll probably add up something bigger but as for now, it seems to run fine with the smallest, yet complete example.

### The benefits of the bundleRenderer

When using the classic renderer, the output of the script will only contain the html related to our application, such as :

```html
<div id="app" data-server-rendered="true"><div class="nav"><a href="/" class="router-link-active">home</a> <a href="/about" class="router-link-exact-active router-link-active">about</a></div> <pre>{
  &quot;current&quot;: &quot;&quot;,
  &quot;route&quot;: {
    &quot;name&quot;: &quot;about&quot;,
    &quot;path&quot;: &quot;/about&quot;,
    &quot;hash&quot;: &quot;&quot;,
    &quot;query&quot;: {},
    &quot;params&quot;: {},
    &quot;fullPath&quot;: &quot;/about&quot;,
    &quot;meta&quot;: {},
    &quot;from&quot;: {
      &quot;name&quot;: null,
      &quot;path&quot;: &quot;/&quot;,
      &quot;hash&quot;: &quot;&quot;,
      &quot;query&quot;: {},
      &quot;params&quot;: {},
      &quot;fullPath&quot;: &quot;/&quot;,
      &quot;meta&quot;: {}
    }
  }
}</pre> <div class="view">about page!</div></div>
```

But when using the bundle renderer, the full html template will be generated along, and asset loading managment, hydration of the client state along with style injection will be done automatically :

```html
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="preload" href="main.js" as="script"><link rel="prefetch" href="about.js"><link rel="prefetch" href="home.js"><style data-vue-ssr-id="03ada772:0">
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  color: #2c3e50;
}
</style></head>
  <body>
    <div id="csr">
      <div id="app" data-server-rendered="true"><div class="nav"><a href="/" class="router-link-active">home</a> <a href="/about" class="router-link-exact-active router-link-active">about</a></div> <pre>{
  &quot;current&quot;: &quot;&quot;,
  &quot;route&quot;: {
    &quot;name&quot;: &quot;about&quot;,
    &quot;path&quot;: &quot;/about&quot;,
    &quot;hash&quot;: &quot;&quot;,
    &quot;query&quot;: {},
    &quot;params&quot;: {},
    &quot;fullPath&quot;: &quot;/about&quot;,
    &quot;meta&quot;: {},
    &quot;from&quot;: {
      &quot;name&quot;: null,
      &quot;path&quot;: &quot;/&quot;,
      &quot;hash&quot;: &quot;&quot;,
      &quot;query&quot;: {},
      &quot;params&quot;: {},
      &quot;fullPath&quot;: &quot;/&quot;,
      &quot;meta&quot;: {}
    }
  }
}</pre> <div class="view">about page!</div></div><script>window.__INITIAL_STATE__={"current":"about","route":{"name":"about","path":"\u002Fabout","hash":"","query":{},"params":{},"fullPath":"\u002Fabout","meta":{},"from":{"name":null,"path":"\u002F","hash":"","query":{},"params":{},"fullPath":"\u002F","meta":{}}}}</script><script src="main.js" defer></script>
    </div>
  </body>
</html>
```

While it may not benefit all the configuration, it's quite convenient to have this options available. I'm considering exporting each parts (relevant to Vue.js SSR template injection APi) in PHP functions which will be convenient to use, although it's kinda silly to serve a template already computed...
