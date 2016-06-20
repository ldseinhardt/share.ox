-- Tabela de níveis de permissão
CREATE TABLE IF NOT EXISTS `levels` (
  `id`    INT(1)      NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de países
CREATE TABLE IF NOT EXISTS `countries` (
  `id`   INT 		     NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  `uf`   VARCHAR(2)  NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de estados
CREATE TABLE IF NOT EXISTS `states` (
  `id`         INT 		     NOT NULL AUTO_INCREMENT,
  `country_id` INT         NOT NULL,
  `name`       VARCHAR(64) NOT NULL,
  `uf`         VARCHAR(2)  NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de cidades
CREATE TABLE IF NOT EXISTS `cities` (
  `id`       INT 		     NOT NULL AUTO_INCREMENT,
  `state_id` INT         NOT NULL,
  `name`     VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`state_id`) REFERENCES `states` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de instituições de ensino
CREATE TABLE IF NOT EXISTS `institutions` (
  `id`      INT          NOT NULL AUTO_INCREMENT,
  `name`    VARCHAR(128) NOT NULL,
  `city_id` INT          NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de cursos
CREATE TABLE IF NOT EXISTS `courses` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS `users` (
  `id`             BIGINT UNSIGNED NOT NULL,
  `first_name`     VARCHAR(32)     NULL DEFAULT '',
  `last_name`      VARCHAR(64)     NULL DEFAULT '',
  `picture`        VARCHAR(500)    NULL DEFAULT '',
  `email`          VARCHAR(255)    NULL DEFAULT '',
  -- ativo ou bloqueado
  `actived`        TINYINT(1)      NULL DEFAULT 1,
  -- nível de acesso ao sistema (1 = usuário comum, 2 = adm)
  `level_id`       INT(1)          NULL DEFAULT 1,
  -- localização
  `city_id`        INT             NULL,
  -- instituição de ensino
  `institution_id` INT             NULL,
  -- curso
  `course_id`      INT             NULL,
  `created`        TIMESTAMP       NULL,
  `modified`       TIMESTAMP       NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`),
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de tipos de documentos
CREATE TABLE IF NOT EXISTS `types` (
  `id`          INT         NOT NULL AUTO_INCREMENT,
  `type`        VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
-- Tabela de categorias de documentos
CREATE TABLE IF NOT EXISTS `categories` (
  `id`          INT         NOT NULL AUTO_INCREMENT,
  `category`    VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
-- Tabela de estados de conservação para documentos
CREATE TABLE IF NOT EXISTS `status` (
  `id`          INT         NOT NULL AUTO_INCREMENT,
  `status`      VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
-- Tabela de documentos
CREATE TABLE IF NOT EXISTS `documents` (
  `id`          INT             NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `title`       VARCHAR(128)    NOT NULL,
  `description` VARCHAR(512)    NULL DEFAULT '',
  `image`       VARCHAR(255)    NULL DEFAULT '',
  `type_id`     INT             NOT NULL,
  `author`      VARCHAR(64)     NULL DEFAULT '',
  `year`        YEAR            NULL,
  `publisher`   VARCHAR(64)     NULL DEFAULT '',
  `status_id`   INT             NULL,
  `created`     TIMESTAMP       NULL,
  `modified`    TIMESTAMP       NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`type_id`) REFERENCES `types` (`id`),
  FOREIGN KEY (`status_id`) REFERENCES `status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de relação categorias e documentos
CREATE TABLE IF NOT EXISTS `categories_documents` (
  `document_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`document_id`, `category_id`),
  FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
