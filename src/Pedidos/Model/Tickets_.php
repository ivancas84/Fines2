<?php

namespace Pedidos;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use \Pedidos\Tickets;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class Tickets_ extends Tickets
{

    /** 
     * Generates a QR code image file for the ticket.
     * 
     * @return string The file path to the generated QR code image. Don't forget to delete the temporary file after use with unlink().
     */
    public function generateQR(): string
    {
        $url = "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" 
        . $this->id . "&auth-code=" 
        . $this->auth_code;

        $options = new QROptions([
            'eccLevel' => EccLevel::L,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'scale' => 5,
        ]);

        $qrcode = (new QRCode($options))->render($url);    

        // Save the QR Code as a temporary file
        $qrFile = tempnam(sys_get_temp_dir(), 'qr') . '.png';
        file_put_contents($qrFile, base64_decode(str_replace('data:image/png;base64,', '', $qrcode)));

        return $qrFile;

    } 
}

