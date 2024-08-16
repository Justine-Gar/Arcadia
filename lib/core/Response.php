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
    }

    /** Défini le contenu de la reponse
     * 
     * @param string $content contenu à charger
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /** Définit le code de statut HTTP
     * 
     * @param int $statusCode code de statut HTTP
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /** Envoi réponse au client
     * 
     * methode pour envoyé le code status, en_tete et contenu
     */
    public function send()
    {   
        $this->setDefaultSecurityHeaders();
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->content;
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
        $this->headers['Content-Security-Policy'] = "default-src 'self'";
    }

    /** Prépare et envoie une réponse en JSON
     * 
     * @param mixed $data Donnée à encoder en JSON
     */
    public function json($data)
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->setContent(json_encode($data));
    }
}