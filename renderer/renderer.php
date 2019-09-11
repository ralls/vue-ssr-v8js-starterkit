<?php

  // v8 setup
  use Chenos\V8JsModuleLoader\ModuleLoader;

  $loader = new ModuleLoader($frontend . '/dist'); // this is equivalent to chdir
  $loader->setExtensions('.js', '.json'); // remove the needs to load

  $loader->addVendorDir(__DIR__ . '/node'); // this is the minimum amount of polyfills required to patch down our build
  $loader->addVendorDir($frontend . '/node_modules'); // link to natural frontend dependencies

  $v8 = new V8Js();
  $v8->setModuleNormaliser([$loader, 'normaliseIdentifier']);
  $v8->setModuleLoader([$loader, 'loadModule']);

  // setup internals
  $v8->executeString("
    require('polyfills');

    const window = undefined;

    const process = { env: {
      VUE_ENV: 'server',
      NODE_ENV: 'dev',
    } };

    this.global = {
      process: process,
    };
  ");

  $v8->executeString("
    const FROM_PHP = {
      url: '" . $_SERVER['REQUEST_URI'] . "',
      template: `" . file_get_contents($frontend . '/public/index.html') ."`,
    }
  ");

  ob_start();

  try {
    $v8->executeString(file_get_contents(__DIR__ . '/renderer.js'));
  } catch (Exception $e) {
    var_dump($e);
  }

  ob_end_flush();
