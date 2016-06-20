<?php
  $app->get('/my/', function() use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    $user = $app['db']->fetchAssoc("
      SELECT
        *
      FROM
        users
      WHERE
        id = ?
    ", [$userLogged->id]);

    if (!$user) {
      return $app->redirect('/');
    }

    return $app['twig']->render('my_profile.twig', [
      'page' => 'my_profile',
      'userLogged' => $userLogged,
      'user' => $user
    ]);
  })
    ->bind('my_profile');
