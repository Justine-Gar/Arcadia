<?php
namespace App\Models;

enum Role: string {
  case Admin = 'Admin';
  case Staff = 'Staff';
  case Veto = 'Veto';
}