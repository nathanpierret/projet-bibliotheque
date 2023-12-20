# UserStory de ListerMedias

**En tant que** bibliothécaire  
**Je veux** lister les nouveaux médias  
**Afin de** les rendre disponible aux adhérents de la bibliothèque

## Critères d'acceptation

### Valeurs retournées

Pour chaque média présent dans la liste, seules les informations suivantes devront être retournées :  

- L'id du média
- Le titre du média
- Le statut du média
- La date de création du média (date à laquelle il a été créé dans la BDD)
- Le type de média (Livre, Blu-Ray ou Magazine)

### Ordre de tri

La liste devra être triée par date de création décroissante.  

## Classe à implémenter

La UserStory nécessite la création d'une seule classe :  

- La classe **ListerMedias** pour obtenir un tableau d'objets **Média** ayant le statut **Nouveau**, ordonnées par **dateCreation** dans l'ordre décroissant.  

## Classe ListerMedias

Cette classe recherche dans la base de données tous les enregistrements de la table **Media** ayant le statut **Nouveau**, les ajoute dans un tableau d'objets **Média** et les ordonne par **dateCreation** décroissantes.  

## Attribut

### $entityManager

- Type : Classe **EntityManagerInterface**.
- Utilisation : Accéder à l'outil **EntityManager** de Doctrine pour récupérer les données dans la base de données.

## Méthode

### execute

- Aucun paramètre
- Renvoie un tableau associatif d'objets **Média** ayant le statut **Nouveau** ordonnés par **dateCreation** décroissantes.  

## Commandes

### Tests d'intégration
Voici la commande à utiliser pour visualiser les tests d'intégration de l'User Story :
```batch
  composer test-list-new
```

### Silly
Voici la commande Silly qui utilise l'User Story :
```batch
  php app.php biblio:list:Media:new
```