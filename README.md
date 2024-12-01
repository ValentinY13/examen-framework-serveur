# 1\. Cloner le dépôt GitHub

Depuis le terminal, exécutez la commande suivante pour cloner le projet dans un dossier local :

```bash
git clone https://github.com/username/mon-projet-symfony.git
```

## 2\. Installer les dépendances

Placez-vous dans le dossier cloné :

```bash
cd mon-projet-symfony
```

Ensuite, installez les dépendances PHP avec Composer :

```bash
composer install
```

## 3\. Configurer la base de données

Créez la base de données définie dans DATABASE_URL :

```bash
php bin/console doctrine:database:create
```

Exécutez les migrations pour mettre à jour la structure de la base de données :

```bash
php bin/console make:migration
```

```bash
php bin/console doctrine:migrations:migrate
```

## 4\. Lancer le serveur

```bash
symfony server:start
