<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->get('/library/{id}/', function($id, Request $request) use($app, $userLogged) {
    if (!$userLogged) {
      return $app->redirect('/login/');
    }

    $user = $app['db']->fetchAssoc("
      SELECT
        users.*,
        institutions.name as 'institution_name',
        institutions.shortname as 'institution_shortname',
        courses.name as 'course',
        cities.name as 'city',
        states.name as 'state_name',
        states.uf as 'state_uf',
        countries.name as 'country_name',
        countries.uf as 'country_uf'
      FROM
        users
        LEFT JOIN institutions ON (users.institution_id = institutions.id)
        LEFT JOIN courses ON (users.course_id = courses.id)
        LEFT JOIN cities ON (users.city_id = cities.id)
        LEFT JOIN states ON (cities.state_id = states.id)
        LEFT JOIN countries ON (states.country_id = countries.id)
      WHERE
        users.id = ?
    ", [$id]);

    $documents = $app['db']->fetchAll("
      SELECT
        documents.id,
        documents.title,
        documents.image,
        documents.author,
        documents.type_id
      FROM
        documents
        LEFT JOIN types ON (documents.type_id = types.id)
        LEFT JOIN status ON (documents.status_id = status.id)
      WHERE
        documents.user_id = ?
      ORDER BY documents.modified DESC
    ", [$id]);

    if (!$user) {
      return $app->redirect('/');
    }

    return $app['twig']->render('library.twig', [
      'page' => ($userLogged->id == $id) ? 'my_library' : 'library',
      'userLogged' => $userLogged,
      'user' => $user,
      'documents' => $documents,
      'id' => $id
    ]);
  })
    ->value('id', $userLogged->id)
    ->bind('library');
