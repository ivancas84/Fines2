<?php

namespace Pedidos\Model;

use \Pedidos\Model\Tickets;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;



class Tickets_ extends Tickets
{

    /** 
     * Generates a QR code image file for the ticket.
     * 
     * @return string The file path to the generated QR code image. Don't forget to delete the temporary file after use with unlink().
     */
    public function generateQRCode(): string
    {
        $url = "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" 
        . $this->id . "&auth-code=" 
        . $this->auth_code;

        $options = new QROptions([
            'eccLevel' => EccLevel::L,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'scale' => 5,
        ]);

        return (new QRCode($options))->render($url);    
    } 

    public function generateQRFile(): string
    {
        $qrcode = $this->generateQRCode();

        // Save the QR Code as a temporary file
        $qrFile = tempnam(sys_get_temp_dir(), 'qr') . '.png';
        file_put_contents($qrFile, base64_decode(str_replace('data:image/png;base64,', '', $qrcode)));

        return $qrFile;

    } 
}

