<?php
  $app->get('/notifications/', function() use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    return $app['twig']->render('notifications.twig', [
      'page' => 'notifications',
      'userLogged' => $userLogged
    ]);
  })
    ->bind('notifications');
