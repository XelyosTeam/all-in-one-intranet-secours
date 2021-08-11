# On regarde si le serveur est dispo
clear  # On nettoye la console avant
$mysqlServer = Get-Process mysqld -ErrorAction SilentlyContinue
if (!$mysqlServer) {
  echo "Aucun serveur sql detecte"
  return
}

$folders = @('.\cache\')

# Boucle pour chaque élément de folders
foreach ($folder in $folders) {
  # On vérifie que le fichier existe
  if (Test-Path -Path $folder) {
    # On supprime le fichier
    echo "Suppression de $folder"
    rd -r $folder
  }
}

echo "Lancement du serveur"
php -S 127.0.0.1:80
