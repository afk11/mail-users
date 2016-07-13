INSERT INTO virtual_domains (name) VALUES
 ('example.com'),
 ('email.org')
 ;

INSERT INTO virtual_users (domain_id, password, email) VALUES
 (2, "$6$7afb40eaf9961bda$eUauEdhh/.S.FQEZqNUsXz7f.gR3PDjjx7aB7NLR7u1OQ/iC0umGEE5BI95A.fT.0iEWteNd.1vE4dz9RLijT/", 'admin@email.org'),
 (2, "$6$cad949f6fb10ed52$SwO4uZ00YrCq5fyiHJcTcAIvuvpsdFfrdMIC.87JwYLsnATt6TL/rCBdlTjeFaeCXF2eLXRyaKBggZ.8rgPVM.", 'user@email.org'),
 (2, "$6$32c83249e9882b57$U8qmWN1pfMigQ/Ygosm1sTVVU/qWqBbm.RobDbMi810N8WvorL//5BryO/pN/S3wLRdfanD.8CjCqAjpCnN4b0", 'another@email.org')
 ;

INSERT INTO virtual_aliases (domain_id, source, destination) VALUES
 (2, 'alias1.user@email.org'. 'user@email.org')
 (2, 'spam@email.org'. 'user@email.org')
 ;