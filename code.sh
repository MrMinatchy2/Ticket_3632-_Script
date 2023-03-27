#!/bin/bash

# Vérifie que les deux arguments sont bien présents
if [ $# -ne 2 ]; then
  echo "Usage: $0 <fichier> <répertoire>"
  exit 1
fi

# Vérifie que le premier argument est un fichier existant
if [ ! -f "$1" ]; then
  echo "Erreur : le premier argument doit être un fichier existant"
  exit 1
fi

# Vérifie que le deuxième argument est un répertoire existant
if [ ! -d "$2" ]; then
  echo "Erreur : le deuxième argument doit être un répertoire existant"
  exit 1
fi

# Boucle sur tous les fichiers du répertoire
for fichier in "$2"/*; do
  # Vérifie que le fichier est bien un fichier régulier
  if [ -f "$fichier" ]; then
    # Copie le contenu du premier fichier dans le fichier courant
    cat "$1" > "$fichier"
  fi
done
