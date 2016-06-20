<?php
  use Symfony\Component\HttpFoundation\Request;

  $app->post('/register/', function(Request $request) use($app, $userLogged, $userId) {
    if (!$userId) {
      return false;
    }

    $firstname = $app->escape($request->get('firstname'));
    $lastname = $app->escape($request->get('lastname'));
    $email = $app->escape($request->get('email'));
    $picture = $request->get('picture');

    if ($userLogged) {
      $app['db']->update('users', [
        'first_name' => $firstname,
        'last_name' => $lastname,
        'picture' => $picture,
        'email' => $email,
        'created' => date('Y-m-d H:i:s')
      ], [
        'id' => $userId
      ]);
    } else {
      $app['db']->insert('users', [
        'id' => $userId,
        'first_name' => $firstname,
        'last_name' => $lastname,
        'picture' => $picture,
        'email' => $email,
        'created' => date('Y-m-d H:i:s')
      ]);
    }

    return true;
  });
