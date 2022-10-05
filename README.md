# Sujet-FrenchFounders-2022
Développer une API REST : 
- faire un endpoint pour l'enregistrement d'utilisateurs (nom/prénom/email unique/société/password).
- faire un endpoint de connexion (email/password) fait
- faire un endpoint /me pour récupérer les informations du user connecté (nom/prenom/email/societe) Fait
- cet utilisateur devra être enregistré en base de données 
- une notification de type slack devra être envoyé 
- un email de confirmation devra être envoyé à l'utilisateur 


//Pour Docker

"docker-compose up -d"                //pour lancer la base docker

"symfony var:export --multiline"      //pour récuperer le database_URL, le copier dans .env.local pour que ça soit plus rapide

//Faire pour doctrine
"php bin/console doctrine:database:create" 			
"php bin/console"                                       
"php bin/console doctrine:migrations:migrate"


"php ./bin/console messenger:consume"  //pour que les mails et les notifs s'envoie et ne reste pas stocké

- Que faudrait-il changer/optimiser sur cette api/infra pour encaisser +500 appels/seconde ?

Optimisez les serveurs, utiliser des listes de contrôle pour vérifier que l'application et les serveurs soient configurés pour des performances maximales, mettre en cache les données et éviter les appels d'API inutiles pour accélérer les choses dans mon api par exemple les messages qui sont stockés pour être envoyé plus tard pour pas ralentir l'ajout des utilisateurs

- Que faudrait-il faire pour sécuriser au maximum cette api ?

Utiliser des événements  des listeners ou subscribers pour les utilisateurs, utiliser la protection CSRF pour les formulaires, protéger tous les urls avec des access_control(s).


Heure passé 16h
