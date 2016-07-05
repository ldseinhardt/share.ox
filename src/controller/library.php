<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->get('/library/{id}/', function($id, Request $request) use($app, $userLogged) {
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

    return $app['twig']->render('library.twig', [
      'page' => ($userLogged->id == $id) ? 'my_library' : 'library',
      'userLogged' => $userLogged,
      'user' => $user,
      'id' => $id
    ]);
  })
    ->value('id', $userLogged->id)
    ->bind('library');
