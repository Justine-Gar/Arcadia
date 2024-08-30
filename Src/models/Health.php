<?php
namespace App\Models;

enum Health: string {
  case Bonne = 'Bonne santé';
  case Convalescence = 'Convalescence';
  case Medical = 'Sous traitement Médical';
  case Observation = 'Sous observation';
  case Enrichissement = 'Enrichissement environnemental';
  case Quarantaine = 'En quarantaine';
  case Gestation = 'En gestation';
  case Mue = 'En période de mue';
  case Rehabilitation = 'En réhabilitation';
  case Vieillesse = 'Vieillesse';
}