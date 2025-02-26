const API_URL = 'generate_vcard.php';

document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const hexId = urlParams.get('id');

    // Cargar el archivo JSON
    fetch('data.json')
        .then(response => response.json())
        .then(data => {
            const profiles = data.profiles;
            const profileContainer = document.getElementById('contactsContainer');

            if (profiles[hexId]) {
                const profile = profiles[hexId];
                const card = loadContacts(profile);
            } else {
                profileContainer.innerHTML = '<p>Perfil no encontrado</p>';
            }
        })
        .catch(error => console.error('Error al cargar el JSON:', error));
});

// Función para cargar los contactos desde el servidor
function loadContacts(profile) {
    // Mostrar mensaje de carga
    $('#contactsContainer').html('<div class="loading">Cargando contacto...</div>');
    
    // Realizar petición AJAX para obtener los contactos
    $('#contactsContainer').empty();
            
    createContactCard(profile);

    setupDownloadButtons();
}

// Función para crear una tarjeta de contacto a partir del template
function createContactCard(contact) {
    const template = document.getElementById('contact-template');
    const card = document.importNode(template.content, true);
    
    const cardElement = card.querySelector('.contact-card');
    cardElement.setAttribute('data-id', contact.id);
    
    const img = card.querySelector('.contact-img');
    img.src = contact.img || 'images/default.jpg';
    img.alt = contact.name + ' ' + contact.lastName;
    
    card.querySelector('.contact-name').textContent = contact.name;
    card.querySelector('.contact-lastName') .textContent = contact.lastName;
    card.querySelector('.contact-title').textContent = contact.title;

    card.querySelector('.phone span').textContent = contact.phone;
    card.querySelector('.email span').textContent = contact.email;
    card.querySelector('.website span').textContent = contact.website;
    
    document.getElementById('contactsContainer').appendChild(card);
}

// Configurar eventos de los botones de descarga
function setupDownloadButtons() {
    $('.download-btn').on('click', function() {
        // Obtener el ID del contacto
        const contactId = $(this).closest('.contact-card').data('id');
        
        // Cambiar el texto del botón para indicar que se está descargando
        const $button = $(this);
        const originalText = $button.text();
        $button.text('Descargando...').prop('disabled', true);
        
        // Crear un iframe oculto para la descarga
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = API_URL + '?contact_id=' + contactId;
        document.body.appendChild(iframe);
        
        // Restaurar el botón después de un breve periodo
        setTimeout(function() {
            $button.text(originalText).prop('disabled', false);
            // Eliminar el iframe después de un tiempo para asegurar que la descarga comience
            setTimeout(function() {
                document.body.removeChild(iframe);
            }, 2000);
        }, 1000);
    });
}

// Función para compartir la URL de la página
function shareUrl() {
    const url = window.location.href;

    if (navigator.share) {
        // Si el navegador soporta la API de compartir
        navigator.share({
            title: "Match Alianzas",
            url: url
        })
        .then(() => console.log('URL compartida con éxito'))
        .catch((error) => console.error('Error al compartir:', error));
    } else {
        // Si el navegador no soporta la API de compartir, copia la URL al portapapeles
        navigator.clipboard.writeText(url)
            .then(() => alert('URL copiada al portapapeles: ' + url))
            .catch((error) => console.error('Error al copiar la URL:', error));
    }
}

// Función para enviar un correo electrónico
function sendMailto() {
    const emailParagraph = document.querySelector('.contact-info.email');
    const email = emailParagraph.querySelector('span').textContent;
    window.location.href = "mailto:" + email;;
}

// Función para llamar al número de teléfono
function callto() {
    const phoneParagraph = document.querySelector('.contact-info.phone');
    const phone = phoneParagraph.querySelector('span').textContent;
    window.location.href = "tel:" + phone;;
}