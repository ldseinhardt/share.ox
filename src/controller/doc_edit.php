<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->match('/doc/{id}/edit/', function($id, Request $request) use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
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

    if ($request->isMethod('POST')) {
      $document = [
        'title' => $app->escape($request->get('title')),
        'description' => $app->escape($request->get('description')),
        'type_id' => $app->escape($request->get('type_id')),
        'author' => $app->escape($request->get('author')),
        'status_id' => $app->escape($request->get('status_id')),
        'year' => $app->escape($request->get('year')),
        'publisher' => $app->escape($request->get('publisher')),
        'image' => $document['image']
      ];

      $type_other = $app->escape($request->get('type_other'));
      $categories = $request->get('categories');

      $image = $request->files->get('image');

      $max_file_size = 1024 * 1024 * 1;

      $formats = [
        'image/jpg',
        'image/jpeg',
        'image/png',
        'image/gif'
      ];

      $path = WWW_ROOT . '/uploads/';

      if ($image && ($image->getClientSize() <= $max_file_size) && in_array($image->getClientMimeType(), $formats)) {
        if ($document['image']) {
          unlink($path . $document['image']);
          $document['image'] = '';
        }
        $format = str_replace('image/', '.', $image->getClientMimeType());
        $format = str_replace('jpeg', 'jpg', $format);
        $document['image'] = md5($userLogged->id) . md5(time()) . $format;
        $image->move($path, $document['image']);
      }

      $document['user_id'] = $userLogged->id;
      $document['modified'] = date('Y-m-d H:i:s');

      if (!$document['year']) {
        $document['year'] = NULL;
      }

      if ($document['type_id'] == 4) {
        $query = $app['db']->fetchAssoc("
          SELECT
            id
          FROM
            types
          WHERE
            type = ?
        ", [$type_other]);
        if ($query && $query['id']) {
          $document['type_id'] = $query['id'];
        } else {
          $app['db']->insert('types', [
            'type' => $type_other
          ]);
          $document['type_id'] = $app['db']->lastInsertId();
        }
      }

      if (!$document['status_id']) {
        $document['status_id'] = NULL;
      }

      $app['db']->update('documents', $document, ['id' => $id]);

      $app['db']->delete('categories_documents', [
        'document_id' => $id
      ]);
      if (count($categories)) {
        foreach ($categories as $i => $category_id) {
          $app['db']->insert('categories_documents', [
            'document_id' => $id,
            'category_id' => $category_id
          ]);
        }
      }
      return $app->redirect('/doc/' . $id . '/');
    }

    return $app['twig']->render('doc_edit.twig', [
      'page' => 'doc_edit',
      'userLogged' => $userLogged,
      'document' => $document,
      'type_other' => $type_other,
      'categories' => $categories
    ]);
  }, 'GET|POST')
    ->bind('doc_edit');
