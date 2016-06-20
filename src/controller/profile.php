<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->get('/profile/{id}/', function(Request $request) use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    return $app['twig']->render('profile.twig', [
      'page' => 'profile',
      'userLogged' => $userLogged
    ]);
  })
    ->bind('profile');
