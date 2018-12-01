<?php

namespace Ontic\Iuris\Plugin;

use Ontic\Iuris\Interfaces\IPlugin;
use Ontic\Iuris\Model\AnalysisDetail;
use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\Flag;

class SslCertificate implements IPlugin
{
    /**
     * @param AnalysisRequest $request
     * @param array $config
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request, array $config)
    {
        $url = $request->getWebdriver()->getCurrentURL();
        $orignal_parse = parse_url($url, PHP_URL_HOST);
        $get = stream_context_create(['ssl' => ['capture_peer_cert' => true]]);

        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line) {
            throw new \ErrorException($err_msg, 0, $err_severity, $err_file, $err_line);
        }, E_WARNING);

        try
        {
            stream_socket_client('ssl://' . $orignal_parse . ':443', $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
            $score = 100;
            $message = '✓ SSL Certificate correct and up to date.';
        } 
        catch (\Exception $e)
        {
            if (strpos($e->getMessage(), 'SSL Certificate verify failed') === false)
            {
                $score = 0;
                $message = "✗ SSL Certificate failed.\nCheck your SSL installation in " . $url;
            } 
            else
            { //Certificado invalido;
                $message = "✗ SSL Certificate invalid or outdate\n Please install a valid certificate and up to date for domain " . $url;
                $score = 0;
            }
        }
        
        restore_error_handler();

        return new AnalysisDetail(
            $this->getCode(),
            Flag::Scorable,
            $score,
            $message
        );
    }

    /**
     * @return string
     */
    function getCode()
    {
        return 'ssl_certificate';
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return 'SSL';
    }
}