<?php

namespace lib\core;

class Responce
{
  private $headers = [];
    private $content = '';
    private $statusCode = 200;

    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function send()
    {
        // Définir le code de statut
        http_response_code($this->statusCode);

        // Définir les en-têtes par défaut pour la sécurité
        $this->setDefaultSecurityHeaders();

        // Envoyer tous les en-têtes
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Envoyer le contenu
        echo $this->content;
    }

    private function setDefaultSecurityHeaders()
    {
        $this->headers['X-Frame-Options'] = 'DENY';
        $this->headers['X-XSS-Protection'] = '1; mode=block';
        $this->headers['X-Content-Type-Options'] = 'nosniff';
        $this->headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        $this->headers['Content-Security-Policy'] = "default-src 'self'";
    }

    public function json($data)
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->setContent(json_encode($data));
    }
}