<?php

// Création d'une fonction qui vérifie que le mot de passe comporte au moins 8 caractères dont 1 minuscule, 1 majuscule, 1 chiffre et un caractère spécial.
function isValidPassword($validPassword)
{
    return preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/', $validPassword);
}