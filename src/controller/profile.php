<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->get('/profile/{id}/', function($id, Request $request) use($app, $userLogged) {
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
    ", [$id]);

    if (!$user) {
      return $app->redirect('/');
    }

    return $app['twig']->render('profile.twig', [
      'page' => 'profile',
      'userLogged' => $userLogged,
      'user' => $user
    ]);
  })
    ->bind('profile');
