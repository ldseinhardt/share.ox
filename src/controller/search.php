<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->get('/search/', function(Request $request) use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    $search = $app->escape($request->get('q'));
    $type = $app->escape($request->get('type'));
    $label = $app->escape($request->get('label'));
    $status = $app->escape($request->get('status'));
    $sortby = $app->escape($request->get('sortby', 2));

    $filters = [
      "1 = 1",  
    ];

    if ($search) {
      $filters[] = "(
        documents.title LIKE '%{$search}%' OR 
        documents.description LIKE '%{$search}%' OR 
        documents.author LIKE '%{$search}%' OR 
        documents.year LIKE '%{$search}%' OR 
        documents.publisher LIKE '%{$search}%' OR 
        users.first_name LIKE '%{$search}%' OR 
        users.last_name LIKE '%{$search}%' OR 
        users.email LIKE '%{$search}%' OR 
        types.type LIKE '%{$search}%' OR 
        status.status LIKE '%{$search}%'
      )";
    }

    if ($type && $type < 4) {
      $filters[] = "documents.type_id = {$type}";
    } else if ($type >= 4) {
      $filters[] = "documents.type_id > 3";
    }

    if ($status) {
      $filters[] = "documents.status_id = {$status}";
    }

    $sort = '';

    switch ($sortby) {
      case 1:
        $sort = 'documents.title ASC';
        break;
      case 2;
        $sort = 'documents.modified DESC';
        break;
    }

    $filter = implode(' AND ', $filters);

    $documents = $app['db']->fetchAll("
      SELECT
        documents.id,
        documents.title,
        documents.image,
        documents.author,
        documents.type_id,
        users.first_name AS user_first_name,
        users.picture AS user_picture
      FROM
        documents
        LEFT JOIN users ON (documents.user_id = users.id)
        LEFT JOIN types ON (documents.type_id = types.id)
        LEFT JOIN status ON (documents.status_id = status.id)
      WHERE
        {$filter}
      ORDER BY {$sort}
    ");

    return $app['twig']->render('search.twig', [
      'page' => 'search',
      'userLogged' => $userLogged,
      'search' => $search,
      'type' => $type,
      'label' => $label,
      'status' => $status,
      'sortby' => $sortby,
      'documents' => $documents ? $documents : []
    ]);
  })
    ->bind('search');
