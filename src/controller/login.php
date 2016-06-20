<?php
  $app->get('/login/', function() use($app) {
    return $app['twig']->render('login.twig', [
      'page' => 'login'
    ]);
  })
    ->bind('login');
