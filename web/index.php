<?php
  define('APP_DIR', __DIR__ . '/..');
  define('SRC_DIR', APP_DIR . '/src');
  define('WWW_ROOT', dirname(__FILE__));

  require_once APP_DIR . '/vendor/autoload.php';
  require_once SRC_DIR . '/settings.php';

  use Silex\Application;
  use Silex\Provider\HttpCacheServiceProvider;
  use Silex\Provider\TwigServiceProvider;
  use Silex\Provider\DoctrineServiceProvider;
  use Silex\Provider\ValidatorServiceProvider;
  use Facebook\FacebookJavaScriptLoginHelper;
  use Facebook\Helpers\FacebookRedirectLoginHelper;

  $app = new Application();

  $app->register(new HttpCacheServiceProvider(), [
    'http_cache.cache_dir' => APP_DIR . '/temp/cache/http_cache/',
    'http_cache.esi'       => NULL
  ]);

  $app->register(new TwigServiceProvider(), [
    'twig.options' => [
      'cache' => APP_DIR . '/temp/cache/twig/'
    ],
    'twig.path' => SRC_DIR . '/view/'
  ]);

  $app->register(new DoctrineServiceProvider(), [
    'db.options' => $db_options
  ]);

  $app->register(new ValidatorServiceProvider());

  require_once SRC_DIR . '/model.php';

  $fb = new Facebook\Facebook([
    'app_id' => '1725608467696499',
    'app_secret' => '2a742a2792ac98878405a4417a7eaf60',
    'default_graph_version' => 'v2.6'
  ]);

  $jsHelper = $fb->getJavaScriptHelper();

  $userId = $jsHelper->getUserId();

  if ($userId) {
    $userLogged = $app['db']->fetchAssoc("
      SELECT
        *,
  		  CONCAT(first_name, ' ', last_name) AS full_name
      FROM
        users
      WHERE
        id = ?
    ", [$userId]);

    if ($userLogged) {
      $userLogged = (object) $userLogged;
    }
  }

  require_once SRC_DIR . '/controller.php';

  $app->get('/{type}/{file}', function($type, $file) use($app) {
    switch ($type) {
      case 'assets':
        $path = '/assets/';
        break;
      case 'vendor':
        $path = '/../bower_components/';
        break;
      default:
        $path = '/';
    }

    $filename = WWW_ROOT . $path . $file;

    if (!file_exists($filename)) {
      return $app->abort(404);
    }

    $headers = [];

    switch (pathinfo($filename, PATHINFO_EXTENSION)) {
      case 'css':
        $headers['content-type'] = 'text/css; charset=utf-8';
        break;
      case 'js':
        $headers['content-type'] = 'application/javascript; charset=utf-8';
        break;
      case 'json':
        $headers['content-type'] = 'application/json; charset=utf-8';
        break;
      case 'txt':
        $headers['content-type'] = 'text/plain; charset=utf-8';
        break;
    }

    return $app->sendFile($filename, 200, $headers);
  })
    ->assert('type', 'files|assets|vendor')
    ->assert('file', '.+')
    ->value('type', 'files')
    ->bind('file');

  $app->error(function(Exception $e, $code) use($app) {
    if (!$app['debug']) {
      return $app->redirect('/');
    }
  });

  $app['debug'] = $debug;

  if ($app['debug']) {
    $app->run();
  } else {
    $app['http_cache']->run();
  }
