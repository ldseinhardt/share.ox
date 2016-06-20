<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->get('/doc/{id}/edit/', function(Request $request) use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    return $app['twig']->render('doc_edit.twig', [
      'page' => 'doc_edit',
      'userLogged' => $userLogged
    ]);
  })
    ->bind('doc_edit');
