<?php

namespace lib\core;

/** Classe Response
 * 
 * Gère la requete HTTP à envoyer au client
 * permet de définir les en-tetes, le contenu et le status de la reponse
 */
class Response
{
    private $headers = [];
    private $content = '';
    private $statusCode = 200;

    /** Défini un en-tete HTTP
     * 
     * @param string $name Nom de l'en-tete
     * @param string $value Valeur de l'en-tete
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /** Défini le contenu de la reponse
     * 
     * @param string $content contenu à charger
     */
    public function setContent($content)
    {
        // Si c'est déjà une chaîne JSON, on la garde telle quelle
        if (is_string($content) && $this->isJson($content)) {
            $this->content = $content;
        } 
        // Si c'est un tableau ou un objet, on le convertit en JSON
        else if (is_array($content) || is_object($content)) {
            $this->content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } 
        // Sinon on le convertit en chaîne
        else {
            $this->content = (string) $content;
        }
        return $this;
    }

    /** Définit le code de statut HTTP
     * 
     * @param int $statusCode code de statut HTTP
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /** Envoi réponse au client
     * 
     * methode pour envoyé le code status, en_tete et contenu
     */
    public function send()
    {   
        if (ob_get_length()) ob_clean();
        
        $this->setDefaultSecurityHeaders();
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->content;
        exit;
    }

    /** Définit les en_tete de sécurité
     * 
     * methode privé configuration des en_tete de sécurité
     */
    private function setDefaultSecurityHeaders()
    {
        $this->headers['X-Frame-Options'] = 'DENY';
        $this->headers['X-XSS-Protection'] = '1; mode=block';
        $this->headers['X-Content-Type-Options'] = 'nosniff';
        $this->headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        $this->headers['Content-Security-Policy'] = "default-src 'self'; connect-src 'self'";
    }

    /** Prépare et envoie une réponse en JSON
     * 
     * @param mixed $data Donnée à encoder en JSON
     */
    public function json($data)
    {
        $this->setHeader('Content-Type', 'application/json');
        if (is_array($data) || is_object($data)) {
            $this->content = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $this->content = json_encode(['data' => $data], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        return $this;
    }

    // Fonction utilitaire pour vérifier si une chaîne est du JSON valide
    private function isJson($string) {
        if (!is_string($string)) return false;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}