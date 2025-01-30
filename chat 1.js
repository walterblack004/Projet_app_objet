// Fonction pour chiffrer un message avec le code César
function chiffrerCesar(message, decalage = 3) {
    return message
        .split('')
        .map(char => {
            if (char.match(/[a-z]/i)) {
                const code = char.charCodeAt(0);
                const shift = code >= 65 && code <= 90 ? 65 : 97;
                return String.fromCharCode(((code - shift + decalage) % 26) + shift);
            }
            return char;
        })
        .join('');
}

// Fonction pour déchiffrer un message avec le code César
function dechiffrerCesar(message, decalage = 3) {
    return chiffrerCesar(message, 26 - decalage);
}

// Gestion de l'envoi de message
document.getElementById('chat-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();

    if (message) {
        // Chiffrer le message avant de l'envoyer
        const messageChiffre = chiffrerCesar(message);

        // Envoyer le message chiffré au backend (simulé ici)
        const chatMessages = document.getElementById('chat-messages');
        const newMessage = document.createElement('div');
        newMessage.classList.add('message');
        newMessage.innerHTML = `
            <span class="sender">Vous</span>
            <span class="content">${message}</span>
            <span class="timestamp">${new Date().toLocaleTimeString()}</span>
        `;
        chatMessages.appendChild(newMessage);

        // Effacer le champ de saisie
        messageInput.value = '';

        // Faire défiler vers le bas
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// Gestion de la déconnexion
document.getElementById('logout').addEventListener('click', function () {
    alert('Vous avez été déconnecté.');
    window.location.href = 'accueil.html';
});