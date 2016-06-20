<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->get('/doc/{id}/', function($id) use($app, $userLogged) {
    $document = $app['db']->fetchAssoc("
      SELECT
        documents.id,
        documents.title,
        documents.description,
        documents.image,
        documents.author,
        documents.publisher,
        documents.type_id,
        documents.status_id,
        documents.year,
        documents.user_id,
        users.first_name AS user_first_name,
        users.last_name AS user_last_name,
        users.picture AS user_picture
      FROM
        documents
        LEFT JOIN users ON (documents.user_id = users.id)
        LEFT JOIN types ON (documents.type_id = types.id)
        LEFT JOIN status ON (documents.status_id = status.id)
      WHERE
        documents.id = ?
    ", [$id]);

    if (!$document) {
      return $app->redirect('/');
    }

    $document['categories'] = $app['db']->fetchAll("
      SELECT
        categories.category AS name
      FROM
        categories_documents
        LEFT JOIN categories ON (categories_documents.category_id = categories.id)
      WHERE
        categories_documents.document_id = ?
    ", [$document['id']]);

    return $app['twig']->render('doc_view.twig', [
      'page' => 'doc_view',
      'userLogged' => $userLogged,
      'document' => $document
    ]);
  })
    ->bind('doc_view');
