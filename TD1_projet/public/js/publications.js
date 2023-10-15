function supprimerFeedy(event) {
    let button = event.target;
    let feedy = button.closest(".feedy");
    feedy.remove();

    //Création de l'objet
    let xhr = new XMLHttpRequest();
    let URL = Routing.generate('deletePublication', {"id": button.dataset.publicationId});
    //La méthode utilisée (GET, POST, PUT, PATCH ou DELETE), l'URL et si la requête est asynchrone ou non.
    xhr.open('DELETE', URL, true);
    xhr.onload = function () {
        //Fonction déclenchée quand on reçoit la réponse du serveur.
        //xhr.status permet d'accèder au code de réponse HTTP (200, 204, 403, 404, etc...)
        console.log(xhr.status)
    };
    //On exécute la requête
    //On précise null s'il n'y a pas de données supplémentaires (payload) à envoyer.
    xhr.send(null);
}

let buttons = document.getElementsByClassName("delete-feedy");
Array.from(buttons).forEach(function (button) {
    button.addEventListener("click", supprimerFeedy);
});