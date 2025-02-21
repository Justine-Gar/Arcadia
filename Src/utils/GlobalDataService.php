<?php
namespace App\Utils;

use App\Repositories\TimetableRepository;

class GlobalDataService
{
    private static $instance = null;
    private $timetableRepository;

    private function __construct()
    {
        $this->timetableRepository = new TimetableRepository();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getTimetables(): array
    {
        return $this->timetableRepository->getAllTimetables();
    }
}