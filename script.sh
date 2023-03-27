#!/bin/bash

# Vérifie que le premier argument est un répertoire existant
if [ ! -d "$1" ]; then
  echo "Erreur : le premier argument doit être un répertoire existant"
  exit 1
fi

# Parcourt chaque fichier dans le répertoire et ses sous-répertoires
find "$1" -type f -exec sed -i 's/(\[)([a-zA-Z_]+[a-zA-Z0-9_]*)(\])/$1\"$2\"$3/g' {} +
