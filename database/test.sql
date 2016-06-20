INSERT INTO `users` (`id`, `first_name`, `last_name`, `picture`, `email`, `city_id`, `institution_id`, `course_id`, `created`, `modified`) VALUES
  ( 582834068560701, 'Luan', 'Einhardt', 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xal1/v/t1.0-1/p50x50/12391423_527835154060593_3241434042487947692_n.jpg?oh=74b18a84a4bd76279ba6071404c2f30d&oe=579C9D9D&__gda__=1470651958_4c87b7c1574b05eaa41f564629a47ec4', 'ldseinhardt@gmail.com', 1, 1, 1, NOW(), NOW()),
  (1036298676427868, 'Pedro', 'Matheus', 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xft1/v/t1.0-1/p50x50/11141331_1032641783460224_8803188900816989670_n.jpg?oh=96af4b45f6b3b7542ebe56f86fffce92&oe=57B6B82C&__gda__=1470122587_dbe12546024edd350a4bec702751841f', 'pedro.m.theus@gmail.com', 1, 1, 2, NOW(), NOW()),
  (1088480044505670, 'Marcelle', 'Uliano', 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpt1/v/t1.0-1/p50x50/13062442_1084860921534249_6808499158989989544_n.jpg?oh=5f9afee83d1136b5f17f743d3ead7360&oe=579E805E&__gda__=1474605456_a9c44112a93ddc95d21bd6f604dcbc22', 'marcelle_uliano@hotmail.com', 1, 1, 2, NOW(), NOW());

INSERT INTO `documents` (`id`, `user_id`, `type_id`, `title`, `description`, `created`, `modified`) VALUES
  (1, 582834068560701, 1, 'Livro prático de php', 'blah... blah... blah...' , NOW(), NOW()),
  (2, 582834068560701, 2, 'Cópias de Sociologia', 'blah... blah... blah...', NOW(), NOW()),
  (3, 1036298676427868, 1, 'Xerox de AeP', 'blah... blah... blah...', NOW(), NOW()),
  (4, 1088480044505670, 2, 'Livro de javascript', 'blah... blah... blah...', NOW(), NOW()),
  (5, 1036298676427868, 1, 'Livro de redes', 'blah... blah... blah...', NOW(), NOW());

SELECT
  concat(users.first_name, ' ', users.last_name) AS name,
  users.email,
  institutions.name AS institution,
  courses.name AS course,
  cities.name AS city,
  states.uf as state,
  countries.uf as country
FROM
  users
INNER JOIN
  institutions ON users.institution_id = institutions.id
INNER JOIN
  courses ON users.course_id = courses.id
INNER JOIN
  cities ON users.city_id = cities.id
INNER JOIN
  states ON cities.state_id = states.id
INNER JOIN
  countries ON states.country_id = countries.id
ORDER BY name ASC
