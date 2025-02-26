<?php
/*  Archivo: generate_vcard.php
    Este archivo maneja la generación de vCards para contactos predefinidos
    Permite solicitudes desde el origen de tu sitio (cambia esto según tu dominio)
*/
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Esta función genera un archivo vCard (.vcf) basado en los datos proporcionados
function generateVCard($name, $lastname, $organization, $title, $phone, $email, $website, $address, $photo = '') {
    // Iniciar el contenido del vCard
    $vcard = "BEGIN:VCARD\r\n";
    $vcard .= "VERSION:3.0\r\n";
    $vcard .= "N:" . $lastname . ";" . $name . ";;;\r\n";
    $vcard .= "FN:" . $name . " " . $lastname . "\r\n";
    
    // Agregar organización si existe
    if (!empty($organization)) {
        $vcard .= "ORG:" . $organization . "\r\n";
    }
    
    // Agregar puesto/título si existe
    if (!empty($title)) {
        $vcard .= "TITLE:" . $title . "\r\n";
    }
    
    // Agregar teléfono si existe
    if (!empty($phone)) {
        $vcard .= "TEL;TYPE=CELL:" . $phone . "\r\n";
    }
    
    // Agregar correo si existe
    if (!empty($email)) {
        $vcard .= "EMAIL;TYPE=WORK,INTERNET:" . $email . "\r\n";
    }
    
    // Agregar sitio web si existe
    if (!empty($website)) {
        $vcard .= "URL:" . $website . "\r\n";
    }
    
    // Agregar dirección si existe
    if (!empty($address)) {
        $vcard .= "ADR;TYPE=WORK:;;" . $address . "\r\n";
    }
    
    // Agregar foto si existe (debe ser una URL absoluta o ruta al archivo)
    if (!empty($photo) && file_exists($photo)) {
        $vcard .= "PHOTO;TYPE=JPEG;ENCODING=b:" . base64_encode(file_get_contents($photo)) . "\r\n";
    }
    
    // Agregar fecha de creación
    $vcard .= "REV:" . date('Y-m-d') . "T" . date('H:i:s') . "Z\r\n";
    $vcard .= "END:VCARD";
    
    return $vcard;
}

// Base de datos de contactos predefinidos
function getContacts() {
    return [
        1 => [
            'name' => 'Lenny Yudith',
            'lastname' => 'Gómez Portilla',
            'organization' => 'Match Alianzas',
            'title' => 'Cofundadora',
            'phone' => '+57 311 837 7918',
            'email' => 'lenny.gomez@matchalianzas.com',
            'website' => 'www.matchalianzas.com',
            'address' => '',
            'photo' => 'https://matchalianzas.com/img/team/LennyGomez.png' 
        ],
        2 => [
            'name' => 'Devi Milena',
            'lastname' => 'Rojas Mateus',
            'organization' => 'Match Alianzas',
            'title' => 'Cofundadora',
            'phone' => '+57 313 808 5848',
            'email' => 'ana.garcia@consultoraxyz.com',
            'website' => 'www.matchalianzas.com',
            'address' => '',
            'photo' => 'https://matchalianzas.com/img/team/DeviRojas.png'
        ],
        3 => [
            'name' => 'Andrea Carolina',
            'lastname' => 'Tibaduiza Rodriguez',
            'organization' => 'Match Alianzas',
            'title' => 'Cofundadora',
            'phone' => '+57 311 670 6171',
            'email' => 'andrea.tibaduiza@matchalianzas.com',
            'website' => 'www.matchalianzas.com',
            'address' => '',
            'photo' => 'https://matchalianzas.com/img/team/AndreaTibaduiza.png'
        ],
        4 => [
            'name' => 'María Angélica',
            'lastname' => 'Moreno Franco',
            'organization' => 'Match Alianzas',
            'title' => 'Cofundadora',
            'phone' => '+57 311 290 1798',
            'email' => 'maria.moreno@matchalianzas.com',
            'website' => 'www.matchalianzas.com',
            'address' => '',
            'photo' => 'https://matchalianzas.com/img/team/MariaMoreno.png'
        ],
        5 => [
            'name' => 'Maritza Pilar',
            'lastname' => 'Gómez Plazas',
            'organization' => 'Match Alianzas',
            'title' => 'Asociada',
            'phone' => '+57 311 890 1981',
            'email' => 'maritza.gomez@matchalianzas.com',
            'website' => 'www.matchalianzas.com',
            'address' => '',
            'photo' => 'https://matchalianzas.com/img/team/MaritzaGomez.png'
        ],
        6 => [
            'name' => 'Laura Daniela',
            'lastname' => 'Acevedo Urrego',
            'organization' => 'Match Alianzas',
            'title' => 'Asociada',
            'phone' => '+57 311 581 1509',
            'email' => 'daniela.acevedo@matchalianzas.com',
            'website' => 'www.matchalianzas.com',
            'address' => '',
            'photo' => 'https://matchalianzas.com/img/team/DanielaAcevedo.png'
        ]
    ];
}

// Procesar solicitud cuando se recibe un ID de contacto por GET
if (isset($_GET['contact_id'])) {
    $contact_id = intval($_GET['contact_id']);
    $contacts = getContacts();
    
    // Verificar si el ID es válido
    if (isset($contacts[$contact_id])) {
        $contact = $contacts[$contact_id];
        
        // Generar la vCard
        $vcard_content = generateVCard(
            $contact['name'],
            $contact['lastname'],
            $contact['organization'],
            $contact['title'],
            $contact['phone'],
            $contact['email'],
            $contact['website'],
            $contact['address'],
            isset($contact['photo']) ? $contact['photo'] : ''
        );
        
        // Configurar las cabeceras para la descarga del archivo
        header('Content-Type: text/x-vcard');
        header('Content-Disposition: attachment; filename="' . $contact['name'] . '_' . $contact['lastname'] . '.vcf"');
        header('Content-Length: ' . strlen($vcard_content));
        
        // Enviar el contenido de la vCard
        echo $vcard_content;
        exit;
    } else {
        // ID de contacto no válido
        echo json_encode(['error' => 'Contacto no encontrado']);
        exit;
    }
}

// Si no se proporciona un ID, devolver la lista de contactos en formato JSON
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(array_values(getContacts()));
    exit;
}