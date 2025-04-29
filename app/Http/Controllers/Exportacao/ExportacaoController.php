<?php
namespace App\Http\Controllers\Exportacao;

class ExportacaoController
{

    public function tirarAcentos($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }    

    public function generateFileName($prefix, $string, $extension = '')
    {
        $fileName = $prefix . '_' . mb_strtolower($this->tirarAcentos(str_replace(' ', '_', $string))) . '.' . $extension;
        return $fileName;
    }
    
    public function callDownloadFromStream($content, $fileName, $contentType = '')
    {
        ob_start();

        if(!$contentType || empty($contentType))
        {
            $headers = ['Content-Type: application/json; charset=utf-8'];
        }

        echo $content;

        return response()->streamDownload(function() 
        {    
            $bufferstr = ob_get_contents();
        }, $fileName, $headers);

        ob_end_clean();        
    }

}