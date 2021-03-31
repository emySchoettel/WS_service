<html>
    <head>
        <link rel="stylesheet" type="text/css" href="public.css" />
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script scr="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <style>
            .bigContainer
            {
                display: flex; 
                flex-direction: column;
            }
            .container
            {
                display: flex; 
                flex-direction: row;
                margin-bottom : 0.5em;

            }
            label
            {
                margin-right: 0.5em;
            }
        </style>
    </head>
    <body>
        <div class="bigContainer">
            <h1>Ajouter client</h1>
            <div class="container">
                <label>Numéro client : </label>
                <input type="text" id="ncli"/>
            </div>
            <div class="container">
                <label>Nom client : </label>
                <input type="text" id="nom"/>
            </div>
            <div class="container">
                <label>Adresse client : </label>
                <input type="text" id="adresse"/>
            </div>
            <div class="container">
                <label>Localité client : </label>
                <input type="text" id="localite"/>
            </div>
            <div class="container">
                <label>Cat client : </label>
                <input type="text" id="cat"/>
            </div>
            <div class="container">
                <label>Compte client : </label>
                <input type="text" id="compte"/>
            </div>
            <div class="container">
                <input type="button" value="Créer" onclick="ajoutClient()"/>
            </div>
            <div class="container">
                <input type="button" value="Modifier" onclick="modifierClient()"/>
            </div>
            
        </div>    
        <h1>Liste clients</h1>
        <select id="listedesClients">
            <option value="none">--</option>
        </select>
        <input type="button" value="Supprimer" onclick="suppressionClient();"/>
        <div id="liste_clients_div">
            <ol id="liste_clients">

            </ol>
        </div>
        <script type="text/javascript">
            $(document).ready(function()
            {
                refreshPage();
                addSelection();

            });

            function refreshPage()
            {
                $.get("./services.php", function(data, status)
                {
                    var clients = JSON.parse(data);
                    var listeclient; 
                    for (let i = 0; i < clients.length; i++) {
                        var client = "numcli : " + clients[i].NCLI + " nom : " + clients[i].NOM;
                        listeclient += "<li>" + client + "</li>";         
                    }

                    $("#liste_clients").append(listeclient); 
                })
            }

            function addSelection()
            {
                $.get("./services.php", function(data, status)
                {   
                    var clients = JSON.parse(data);
                    var listeClients; 
                    for (let index = 0; index < clients.length; index++) {

                        listeClients += "<option value=\"" + clients[index].NCLI + "\">" + clients[index].NOM + "</option>"
                    }
                    $("#listedesClients").append(listeClients);
                })
            }

            function ajoutClient()
            {
                let snum = $("#ncli").val(); 
                let snom = $("#nom").val();
                let sadresse = $("#adresse").val();
                let slocalite = $("#localite").val();
                let scat = $("#cat").val();
                let scompte = $("#compte").val();

                var sclients = 
                {
                    NCLI : snum, 
                    NOM : snom, 
                    ADRESSE : sadresse,
                    LOCALITE : slocalite, 
                    CAT : scat,
                    COMPTE : scompte
                };

                $.post("./services.php", sclients, function(data)
                {
                    //console.log(data);
                    location.reload();
                });
            }

            function modifierClient()
            {
                alert("Action irréverssible"); 
                var client = 
                {
                    NCLI : $("#ncli").val() ? $("#ncli").val() : '0000',
                    NOM : $("#nom").val() ? $("#nom").val() : '', 
                    ADRESSE : $("#adresse").val() ? $("#adresse").val() : null, 
                    LOCALITE : $("#localite").val() ? $("#localite").val() : '', 
                    CAT : $("#cat").val() ? $("#cat").val() : null, 
                    COMPTE : $("#compte").val() ? $("#compte").val() : null

                }

                $.ajax({
                        url: './services.php?ncli=' + client.NCLI + "&nom=" + client.NOM + "&adresse=" + client.ADRESSE + "&localite=" + client.LOCALITE + "&cat=" + client.CAT + "&compte=" + client.COMPTE,
                        type: 'PUT',
                        sucess: function(result)
                        {

                        }
                    });
            }
            function suppressionClient()
            {
                alert("Action irréverssible");
                if(document.querySelector('#listedesClients').value != "none")
                {
                    var client = {
                    NCLI : document.querySelector('#listedesClients').value
                    };

                    $.ajax({
                        url: './services.php?ncli=' + client.NCLI,
                        type: 'DELETE',
                        data : {
                            "NCLI" : client
                        },
                        sucess: function(result)
                        {
                            location.reload();
                        }
                    });
                }
                
            }
        </script>
    </body>
    
</html>

<?php

?>
