<?php
  $app->get('/', function() use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    return $app['twig']->render('home.twig', [
      'page' => 'home',
      'userLogged' => $userLogged
    ]);
  })
    ->bind('home');
