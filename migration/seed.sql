INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `email`, `password`, `role`, `active`, `created_at`, `updated_at`, `hash`) VALUES (1, 'anonymus', 'N/A', 'N/A', 'anonymus@budapest.hu', '-', 'anonymus', 1, '2021-09-20 14:53:28', '2021-09-20 14:53:30', NULL);

INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (100, 'RECEIVED', 'Beküldött', 'Beküldött');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (110, 'PUBLISHED', 'Beérkezett, feldolgozásra vár', 'Az ötlet beérkezett, szakmai vizsgálata folyamatban van. ');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (111, 'PUBLISHED_WITH_MOD', 'Beérkezett, feldolgozásra vár (módosított)', 'Az ötlet beérkezett, szakmai vizsgálata folyamatban van. ');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (120, 'PRE_COUNCIL', 'Szakmailag jóváhagyva, tanács elé kerül', 'Az ötletet szakmailag jóváhagytuk, önállóan, vagy másokkal összevonva a közösségi költségvetési tanács elé kerül.');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (130, 'VOTING_LIST', 'Szavazólapra került', 'Az ötletet szakmailag jóváhagytuk, és a közösségi költségvetési tanács is támogatta. Önállóan, vagy másokkal összevonva szavazólistára kerül.');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (140, 'UNDER_CONSTRUCTION', 'Szavazáson nyert, megvalósítás alatt áll', 'Az ötlet a szavazáson nyert, megvalósítása folyamatban van.');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (200, 'READY', 'Megvalósult', 'Folyik az adatok lekérése. Néhány másodperc múlva próbálkozzon újra a kivágással vagy a másolással.');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (510, 'STATUS_REJECTED', 'Nem kapott szakmai jóváhagyást', 'Az ötletet a szakmai vizsgálat során nem hagytuk jóvá, ennek oka az ötlet leírása alatt olvasható.');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (530, 'COUNCIL_REJECTED', 'Szakmai jóváhagyást nyert, tanács nem fogadta be', 'Az ötletet szakmailag jóváhagytuk, a közösségi költségvetési tanács azonban nem támogatta.');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (540, 'NOT_VOTED', 'Szavazólapra került, de szavazáson nem nyert', 'Az ötlet a szavazáson nem nyert.');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (600, 'USER_DELETED', 'Felhasználó törölte', 'A felhasználó törölte');
INSERT INTO `workflow_states` (`id`, `code`, `title`, `description`) VALUES (610, 'TRASH', 'Kuka', 'Trollkodás');

INSERT INTO `workflow_state_extras` (`id`, `code`, `title`, `description`) VALUES (100, 'POLITICIZATION', 'Politizálás', 'Kiszedtünk belőle szövegrészt, politizálás miatt');
INSERT INTO `workflow_state_extras` (`id`, `code`, `title`, `description`) VALUES (110, 'DATAPOLICY', 'Adatkezelés', 'Kiszedtünk belőle szövegrészt, adatkezelés miatt');

INSERT INTO `phases` (`id`, `campaign_id`, `code`, `title`, `description`, `start`, `end`) VALUES (1, 2, 'PRE_IDEATION', 'Ötlet beküldés előtt', 'Ötlet beküldés előtt', '2021-09-14 12:41:01', '2021-09-30 23:59:59');
INSERT INTO `phases` (`id`, `campaign_id`, `code`, `title`, `description`, `start`, `end`) VALUES (2, 2, 'IDEATION', 'Ötlet beküldés', 'Ötlet beküldés', '2021-10-01 00:00:00', '2021-12-31 23:59:59');
INSERT INTO `phases` (`id`, `campaign_id`, `code`, `title`, `description`, `start`, `end`) VALUES (3, 2, 'POST_IDEATION', 'Ötlet beküldés után', 'Ötlet beküldés után', '2030-01-09 23:59:59', '2030-01-09 23:59:59');
INSERT INTO `phases` (`id`, `campaign_id`, `code`, `title`, `description`, `start`, `end`) VALUES (4, 2, 'CO_CONSTRUCTION', 'Feldolgozás', 'Feldolgozás', '2030-01-09 23:59:59', '2030-01-09 23:59:59');
INSERT INTO `phases` (`id`, `campaign_id`, `code`, `title`, `description`, `start`, `end`) VALUES (5, 2, 'PRE_VOTE', 'Szavazás előtt', 'Szavazás előtt', '2030-01-09 23:59:59', '2030-01-09 23:59:59');
INSERT INTO `phases` (`id`, `campaign_id`, `code`, `title`, `description`, `start`, `end`) VALUES (6, 2, 'VOTE', 'Szavazás', 'Szavazás', '2030-01-09 23:59:59', '2030-01-09 23:59:59');
INSERT INTO `phases` (`id`, `campaign_id`, `code`, `title`, `description`, `start`, `end`) VALUES (7, 2, 'POST_VOTE', 'Szavazás után', 'Szavazás után', '2030-01-09 23:59:59', '2030-01-09 23:59:59');
INSERT INTO `phases` (`id`, `campaign_id`, `code`, `title`, `description`, `start`, `end`) VALUES (8, 2, 'PRE_RESULT', 'Eredmény előtt', 'Eredmény előtt', '2030-01-09 23:59:59', '2030-01-09 23:59:59');
INSERT INTO `phases` (`id`, `campaign_id`, `code`, `title`, `description`, `start`, `end`) VALUES (9, 2, 'RESULT', 'Eredmény', 'Eredmény', '2030-01-09 23:59:59', '2030-01-09 23:59:59');
