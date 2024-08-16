<?php

function init_php_session(): bool
{
  if (!session_id())
  {
    session_start();
    session_regenerate_id();
    return true;
  }
  return false;
}

function clean_php_session(): void
{
  session_unset();
  session_destroy();
}

function is_login() {
  //utilisateur et bien connecter
  return true;
}

function is_admin() {
  //utilisateur et bien un administrateur
  return true;
}

function is_veto() {
  //utilisateur est bien vétérinaire
}

function is_staff() {
  //utilisateur est bien un employe
}