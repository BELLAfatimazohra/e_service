<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/index.css">
    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <title>Acceuil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>

<body>
<?php
 include 'assets/include/sidebarProf.php'; 

?>
<script>
       
        var bodyDiv = document.querySelector('.bodyDiv');
        
        
        bodyDiv.innerHTML = `
        <div class="title_guide">
                <h1>Guides</h1>
            </div>
            <hr>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Date de publication</th>
                            <th>Profil visé</th>
                            <th>Version</th>
                            <th>Lien</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Utilisation de l'application eServices sur un smartphone</td>
                            <td>Tarik BOUDAA</td>
                            <td>23/04/2020</td>
                            <td>Enseignant</td>
                            <td>V5.X</td>
                            <td><a href="">Ouvrir</a></td>
                        </tr>
                        <tr>
                            <td>Gestion des documents sur eServices</td>
                            <td>Tarik BOUDAA</td>
                            <td>01/04/2020</td>
                            <td>Enseignant</td>
                            <td>V5.X</td>
                            <td><a href="">Ouvrir</a></td>
                        </tr>
                        <tr>
                            <td>Enregistrement et partage d'une présentation vidéo par google meet</td>
                            <td>CHERRADI MOHAMED</td>
                            <td>01/04/2020</td>
                            <td>Enseignant</td>
                            <td>V5.X</td>
                            <td><a href="">Ouvrir</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    </script>
    
</body>

</html>