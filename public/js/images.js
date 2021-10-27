//j'attends que le dom soit chargé
window.onload = () => {
    //Gestion des liens "supprimer" je vais chercher toutes les balises a qui ont pour attribut data-delete
    //la console me renvoie une liste de noeuds qui  contient mes liens supprimer
    let links = document.querySelectorAll('[data-delete]')
    //console.log(links)
    //je veux boucler sur les liens  avec un for of pour eviter de faire un compteur
    for(link of links){
        link.addEventListener("click",function(e){
            //pour empecher la navigation vers le lien au click
            e.preventDefault()
            //Demande de confirmation
            //remplacement de la pop up par une modale
            if(confirm("voulez-vous vraiment supprimer cette image")){
                //on envoie une requête Ajax vers le href du lien avec la methode DELETE
                //par une Promesse fetch
                fetch(this.getAttribute("href"),{
                    //MES OPTIONS
                    method:"DELETE",
                    //j'envoie des infos en entête => headers
                    //XLMHttpRequest m'evite d'initialiser un obj en le faisant avec un fetch
                    //content type pour dire ce qu'on lui envoie ici du JSON
                    headers: {
                        "X-Requested-With": "XLMHttpRequest",
                        "Content-Type": "application/json"
                    },
                    //ce qu'on envoie à l'url => Tableau converti en obj JSON qui contient le _token (dans UserController) 
                    body: JSON.stringify({"_token":this.dataset.token})
                }).then(
                    //quand la promesse est tenu on recup la reponse en JSON, on la traite
                    //on attend les informations on rajoute alors un .then() à la suite pour recup les données
                    response => response.json()
                ).then(data => {
                    //on stock les données dans data
                    //Nos données de réponse sous forme de tableau soit un tableau data qui contient 'success' soit un tab data qui contient 'error'
                    //Verrif si 'success' si oui on supprime l'élément parent de notre lien cad notre div avec l'image et le bouton
                    if(data.success){
                        this.parentElement.remove()
                    }else{
                        alert(data.error)
                    }
                }).catch(e=>alert(e)) //si la promesse n'est pas tenu
            }
        })
    }
}