Gestionnaire page CRUD
======================

## Présentation

Le gestionnaire CRUD utilise AJAX pour l'ouverture des pages.

La gestion du rendu des pages CRUD est gérés avec les classe js suivantes:
- tabCrudHandler => gestionnaire principal
- tabCrudFormHandler => gestionnaire de la soumission des formulaire
- tabCrudActionHandler => gestionnaire des boutons/liens dans zone écran
- tabModalActionHandler => gestionnaire des boutons/liens dans modale

## Utilisation

### Création

1. On définit une zone de contenu 
2. On ajoute le comportement `tabCrudHandler` à cette zone

``` html
    <div id="crud_action" data-target="http://myapp.dev/main/list">
        <h2>Contenu</h2>
        <p>test</p>
    </div>
    <script>
        $('#crud_action').tabCrudHandler();
    </script>
```

A l'ouverture de la page, le contenu de la zone est modifié.
Les conteneurs `main-zone` et `edit-zone` sont ajoutés.

``` html
    <div id="crud_action" data-target="main/list">
        <div class="main-zone">
            <h2>Contenu</h2>
            <p>test</p>
        </div>
        <div class="edit-zone"></div>
    </div>
```

### Les actions

#### Utilisation

``` html
    <a href="main/add" data-zone-loader>ajouter un élément</a>
    <a href="main/delete/1" data-modal-loader>supprimer</a>
```
##### Paramètres

>   `data-zone-loader` : exécute l'action dans la zone `edit-zone`

>   `data-modal-loader` : exécute l'action dans une modale

### Les formulaires

La gestion des formulaires fournie un mécanisme complet

1.  Traitement ajax de la requete 
2.  Fermeture de la zone `edit-zone`
3.  Rechargement de la zone principale `main-zone` 

#### Utilisation

```html
<form id="myForm" action="main/update/1" method="POST" data-async>
    [...]
    <input type="submit" data-loading-text="Saving..."/>
    <input type="button" data-zone-close value="Cancel"/>
</form>
```

##### Paramètres

>   `data-async` : autorise la gestion ajax de la soumission

>   `data-loading-text` : message afficher lors du chargement de l'image

>   `data-zone-close` : bouton de fermeture de la zone