<?php

namespace App\models;

enum Role: string {
  case Admin = 'Admin';
  case Staff = 'Staff';
  case Veto = 'Veto';
}