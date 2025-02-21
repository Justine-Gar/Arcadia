<?php

namespace App\Controllers;

use lib\core\Logger;
use lib\core\Response;
use App\Utils\GlobalDataService;
use DateTime;
use App\Repositories\ReportRepository;

class Controllers
{
    public function __construct() {}

    protected function render($view, $data = [], $domain = 'pages')
    {
        $reportRepository = new ReportRepository();
        $today = new DateTime();
        $data['todayReports'] = $reportRepository->getReportsByDate($today);
        $data['timetables'] = GlobalDataService::getInstance()->getTimetables();
        return $this->renderView($view, $data, $domain);
    }

    protected function renderAdmin($view, $data = [])
    {
        //Logger::info("Tentative renderAdmin avec vue : " . $view);
        try {
            $reportRepository = new ReportRepository();
            $today = new DateTime();
            $data['todayReports'] = $reportRepository->getReportsByDate($today);
            $data['timetables'] = GlobalDataService::getInstance()->getTimetables();
            return $this->renderView($view, $data, 'dashboard/admin');
        } catch (\Exception $e) {
            //Logger::error("Erreur dans renderAdmin: " . $e->getMessage());
            throw $e;
        }
    }

    protected function renderVeto($view, $data = [])
    {
        //Logger::info("Tentative renderAdmin avec vue : " . $view);
        try {
            $reportRepository = new ReportRepository();
            $today = new DateTime();
            $data['todayReports'] = $reportRepository->getReportsByDate($today);
            $data['timetables'] = GlobalDataService::getInstance()->getTimetables();
            return $this->renderView($view, $data, 'dashboard/veto');
        } catch (\Exception $e) {
            //Logger::error("Erreur dans renderAdmin: " . $e->getMessage());
            throw $e;
        }
    }

    protected function renderStaff($view, $data = [])
    {
        //Logger::info("Tentative renderStaff avec vue : " . $view);
        try {
            $reportRepository = new ReportRepository();
            $today = new DateTime();
            $data['todayReports'] = $reportRepository->getReportsByDate($today);
            $data['timetables'] = GlobalDataService::getInstance()->getTimetables();
            return $this->renderView($view, $data, 'dashboard/staff');
        } catch (\Exception $e) {
            //Logger::error("Erreur dans renderStaff: " . $e->getMessage());
            throw $e;
        }
    }

    protected function renderView($view, $data = [], $domain = 'pages')
    {
        //Logger::info("RenderView appelé avec vue: $view, domaine: $domain");

        $viewPath = BASE_PATH . '/src/views';
        $viewName = $view;
        $fullPath = $viewPath . '/' . $domain . '/' . $viewName . '.php';

        //Logger::info("Chemin complet: $fullPath");

        if (!file_exists($fullPath)) {
            //Logger::error("Fichier vue non trouvé: $fullPath");
            throw new \Exception("Vue non trouvée: $fullPath");
        }

        // Extraction des données
        try {
            extract($data);
        } catch (\Exception $e) {
            //Logger::error("Erreur lors de l'extraction des données: " . $e->getMessage());
            throw $e;
        }

        // Capture du contenu de la vue
        ob_start();
        try {
            include $fullPath;
            $content = ob_get_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            //Logger::error("Erreur lors de l'inclusion de la vue: " . $e->getMessage());
            throw $e;
        }

        // Vérification du contenu
        if (empty($content)) {
            //Logger::warning("Le contenu de la vue est vide: $fullPath");
        }

        // Layout
        $layoutPath = BASE_PATH . '/src/views/layout.php';
        if (!file_exists($layoutPath)) {
            //Logger::error("Layout non trouvé: $layoutPath");
            throw new \Exception("Layout non trouvé: $layoutPath");
        }

        ob_start();
        try {
            include $layoutPath;
            $fullContent = ob_get_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            //Logger::error("Erreur lors de l'inclusion du layout: " . $e->getMessage());
            throw $e;
        }

        $response = new Response();
        $response->setContent($fullContent);
        return $response;
    }
}
