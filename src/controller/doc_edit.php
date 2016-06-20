<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->get('/doc/{id}/edit/', function($id, Request $request) use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    $response = NULL;

    if ($request->isMethod('POST')) {
      // ...

      $response = 'Seu documento foi editado com sucesso!!!';
    }

    $document = $app['db']->fetchAssoc("
      SELECT
        *
      FROM
        documents
      WHERE
        id = ?
    ", [$id]);

    if (!$document || $document['user_id'] != $userLogged->id) {
      return $app->redirect('/');
    }

    $type_other = '';

    if ($document['type_id'] >= 4) {
      $query = $app['db']->fetchAssoc("
        SELECT
          type
        FROM
          types
        WHERE
          id = ?
      ", [$document['type_id']]);
      $type_other = $query['type'];
    }

    $categories = [];

    $query = $app['db']->fetchAll("
      SELECT
        category_id
      FROM
        categories_documents
      WHERE
        document_id = ?
    ", [$document['id']]);

    if ($query && count($query)) {
      $categories = array_map(function($e) {
        return $e['category_id'];
      }, $query);
    }

    return $app['twig']->render('doc_edit.twig', [
      'page' => 'doc_edit',
      'userLogged' => $userLogged,
      'document' => $document,
      'type_other' => $type_other,
      'categories' => $categories,
      'response' => $response
    ]);
  })
    ->bind('doc_edit');
