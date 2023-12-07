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

## Exemple de code

### Lister les médias par dateCreation décroissantes
```php
       // Arrange
        $requeteLivre = new CreerLivreRequete("Mon livre 4","2-1234-5680-2","Quelqun",168);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $requeteMagazine = new CreerMagazineRequete("Mon magazine 3","184","11/11/2023");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $listerMedias = new ListerMedias($this->entityManager);
        $mediaPrecedent = null;
        // Act
        $creerLivre->execute($requeteLivre);
        /*$livre = $this->entityManager->getRepository(Media::class)->findOneBy(["id" => 1]);
        $livre->setDateCreation("21/11/2023");
        $this->entityManager->flush();*/
        $creerMagazine->execute($requeteMagazine);
        $resultat = $listerMedias->execute();
        // Assert
        foreach ($resultat as $media) {
            if ($media == $resultat[0]) {
                $mediaPrecedent = $media;
            } else {
                $this->assertLessThanOrEqual((\DateTime::createFromFormat("d/m/Y",$media["dateCreation"])),(\DateTime::createFromFormat("d/m/Y",$mediaPrecedent["dateCreation"])));
            }
        }
```