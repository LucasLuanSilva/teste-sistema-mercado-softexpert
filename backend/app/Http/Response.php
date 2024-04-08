<?php

namespace App\Http;

class Response {

    /**
     * Código do Status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * CAbeçalho do Response
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteúdo que está sendo retornado
     * @var string
     */
    private $contentType = 'application/json';

    /**
     * Conteúdo do Response
     *  @var mixed
     */
    private $content;

    /**
     * Método responsável por iniciar a classe e definir os valores
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'application/json') {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Método responsável por alterar o content type
     * @param string $contentType
    */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Método responsável por adiconar um registro no cabeçalho
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value) {
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por enviar os headers pro navegador
     */
    private function sendHeaders() {
        http_response_code($this->httpCode == 55000 ? 200 : $this->httpCode);

        foreach ($this->headers as $key=>$value) {
            header($key.': '.$value);
        }
    }

    /**
     * Método responsável por enviar o retorno
     */
    public function sendResponse(){
        $this->sendHeaders();
        
        switch ($this->contentType) {
            case 'application/json':
                echo json_encode($this->content);
                exit;
        }
    }
}