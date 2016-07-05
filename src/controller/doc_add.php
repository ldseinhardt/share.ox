<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->match('/share/', function(Request $request) use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    $document = [
      'title' => $app->escape($request->get('title')),
      'description' => $app->escape($request->get('description')),
      'type_id' => $app->escape($request->get('type_id')),
      'author' => $app->escape($request->get('author')),
      'status_id' => $app->escape($request->get('status_id')),
      'year' => $app->escape($request->get('year')),
      'publisher' => $app->escape($request->get('publisher'))
    ];

    $type_other = $app->escape($request->get('type_other'));
    $categories = $request->get('categories');

    $response = NULL;

    if ($request->isMethod('POST')) {
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
        $format = str_replace('image/', '.', $image->getClientMimeType());
        $format = str_replace('jpeg', 'jpg', $format);
        $document['image'] = md5($userLogged->id) . md5(time()) . $format;
        $image->move($path, $document['image']);
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

      $document['user_id'] = $userLogged->id;
      $document['created'] = date('Y-m-d H:i:s');
      $document['modified'] = date('Y-m-d H:i:s');

      $app['db']->insert('documents', array_filter($document, function($e) {
        return $e;
      }));
      $document_id = $app['db']->lastInsertId();

      if ($document_id && count($categories)) {
        foreach ($categories as $i => $category_id) {
          $app['db']->insert('categories_documents', [
            'document_id' => $document_id,
            'category_id' => $category_id
          ]);
        }
      }

      $response = $app->redirect('/doc/' . $document_id . '/');

      $document = array_map(function($e) {
        return '';
      }, $document);
      $type_other = '';
      $categories = [];
    }

    return $app['twig']->render('doc_add.twig', [
      'page' => 'doc_add',
      'userLogged' => $userLogged,
      'document' => $document,
      'type_other' => $type_other,
      'categories' => $categories,
      'response' => $response
    ]);
  }, 'GET|POST')
    ->bind('doc_add');
