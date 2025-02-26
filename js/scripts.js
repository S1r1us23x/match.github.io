const API_URL = 'generate_vcard.php';

document.addEventListener("DOMContentLoaded", function() {
    // Obtener el parámetro de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const hexId = urlParams.get('id');

    // Cargar el archivo JSON
    fetch('data.json')
        .then(response => response.json())
        .then(data => {
            const profiles = data.profiles;
            const profileContainer = document.getElementById('contactsContainer');

            // Verificar si el color hexadecimal existe en el JSON
            if (profiles[hexId]) {
                const profile = profiles[hexId];
                const card = loadContacts(profile);
                profileContainer.appendChild(card);
            } else {
                // Si no existe, mostrar un mensaje de error
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
    // Clonar el template
    const template = document.getElementById('contact-template');
    const card = document.importNode(template.content, true);
    
    // Configurar los datos del contacto en la tarjeta
    const cardElement = card.querySelector('.contact-card');
    cardElement.setAttribute('data-id', contact.id);
    
    // Imagen
    const img = card.querySelector('.contact-img');
    img.src = contact.img || 'images/default.jpg';
    img.alt = contact.name + ' ' + contact.lastName;
    
    // Nombre y título
    card.querySelector('.contact-name').textContent = contact.name;
    card.querySelector('.contact-lastName') .textContent = contact.lastName;
    card.querySelector('.contact-title').textContent = contact.title;
    
    // Información de contacto
    //card.querySelector('.organization span').textContent = contact.organization;
    card.querySelector('.phone span').textContent = contact.phone;
    card.querySelector('.email span').textContent = contact.email;
    card.querySelector('.website span').textContent = contact.website;
    //card.querySelector('.address span').textContent = contact.address;
    
    // Agregar la tarjeta al contenedor
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