<?php
// Archivo: generate_vcard.php
// Este archivo maneja la generación de vCards para contactos predefinidos

// Permite solicitudes desde el origen de tu sitio (cambia esto según tu dominio)
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
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'organization' => 'Empresa ABC',
            'title' => 'Director General',
            'phone' => '+34612345678',
            'email' => 'juan.perez@empresaabc.com',
            'website' => 'www.empresaabc.com',
            'address' => 'Calle Principal 123, Madrid, España',
            'photo' => 'images/juan.jpg' 
        ],
        2 => [
            'name' => 'Ana',
            'lastname' => 'García',
            'organization' => 'Consultora XYZ',
            'title' => 'Consultora Senior',
            'phone' => '+34623456789',
            'email' => 'ana.garcia@consultoraxyz.com',
            'website' => 'www.consultoraxyz.com',
            'address' => 'Av. Central 456, Barcelona, España',
            'photo' => 'images/ana.jpg'
        ],
        3 => [
            'name' => 'Carlos',
            'lastname' => 'Rodríguez',
            'organization' => 'Tech Solutions',
            'title' => 'Desarrollador Web',
            'phone' => '+34634567890',
            'email' => 'carlos.rodriguez@techsolutions.com',
            'website' => 'www.techsolutions.com',
            'address' => 'Plaza Mayor 78, Valencia, España',
            'photo' => 'images/carlos.jpg'
        ],
        4 => [
            'name' => 'María',
            'lastname' => 'López',
            'organization' => 'Marketing Digital',
            'title' => 'Social Media Manager',
            'phone' => '+34645678901',
            'email' => 'maria.lopez@marketingdigital.com',
            'website' => 'www.marketingdigital.com',
            'address' => 'Calle Nueva 90, Sevilla, España',
            'photo' => 'images/maria.jpg'
        ],
        5 => [
            'name' => 'Javier',
            'lastname' => 'Martínez',
            'organization' => 'Legal Advisors',
            'title' => 'Abogado',
            'phone' => '+34656789012',
            'email' => 'javier.martinez@legaladvisors.com',
            'website' => 'www.legaladvisors.com',
            'address' => 'Gran Vía 345, Bilbao, España',
            'photo' => 'images/javier.jpg'
        ],
        6 => [
            'name' => 'Laura',
            'lastname' => 'Fernández',
            'organization' => 'Diseño Creativo',
            'title' => 'Diseñadora Gráfica',
            'phone' => '+34667890123',
            'email' => 'laura.fernandez@disenocreativo.com',
            'website' => 'www.disenocreativo.com',
            'address' => 'Paseo del Arte 567, Málaga, España',
            'photo' => 'images/laura.jpg'
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