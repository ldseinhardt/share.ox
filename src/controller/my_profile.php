<?php
  $app->get('/my/', function() use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    return $app['twig']->render('my_profile.twig', [
      'page' => 'my_profile',
      'userLogged' => $userLogged
    ]);
  })
    ->bind('my_profile');
