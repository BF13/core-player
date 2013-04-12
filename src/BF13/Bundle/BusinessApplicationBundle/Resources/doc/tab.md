Gestion des onglets
===================

## Présentation

Le rendu des onglets est assurés par TwitterBoostrap

## Chargement asynchrone des onglets

- tabLoaderHandler => gestionnaire de chargement des onglets

### Utilisation

```html
    <ul class="nav nav-tabs" id="myTabs">
        <li class="active"><a href="#properties" data-toggle="tab">Propriétés</a></li>
        <li ><a href="#tab-attributes" data-toggle="tab">Attributs</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="properties">
            <h2>Properties</h2>
            <p>...</p>
        </div>
        <div class="tab-pane" id="tab-attributes" data-tab-handler="crud" data-target="main/item/2/attributes" ></div>
    </div>
    <script>
        $(document).ready(function(){
            $('#myTabs').tabLoaderHandler();
        });
    </script>
```

#### Paramètres

>   `data-tab-handler` : [basic,crud] déclare l'onglet en chargement asynchrone

>   `data-target` : l'url à charger
