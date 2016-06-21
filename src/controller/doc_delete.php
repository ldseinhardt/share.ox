<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->match('/doc/{id}/delete/', function($id) use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    $document = $app['db']->fetchAssoc("
      SELECT
        id,
        user_id
      FROM
        documents
      WHERE
        id = ?
    ", [$id]);

    if (!$document || $document['user_id'] != $userLogged->id) {
      return $app->redirect('/');
    }

    $app['db']->delete('documents', [
      'id' => $id
    ]);

    return $app->redirect('/');
  }, 'GET|POST')
    ->bind('doc_delete');
