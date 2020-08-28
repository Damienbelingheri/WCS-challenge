let app = {



    //on définit ici nos propriétés réutilisables dans notre code

    baseUrl: "http://127.0.0.1:8000/api/",



    //méthode permettant d'initialiser notre application
    init: function () {
        //pour être sûr que tout marche bien
        console.log("coucou depuis l'init");


        //met en place toutes nos mises sous écoute
        app.listenForEvents();

        app.loadArgonautes();
    },

    listenForEvents: function () {
        let FormGroupCat = document.querySelector(".submit");
        FormGroupCat.addEventListener("click", app.handleInsertName);
    },





    loadArgonautes: function () {
        let myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");

        let fetchOptions = {
            method: 'GET',
            mode: 'cors',
            cache: 'no-cache',
            // On ajoute les headers dans les options
            headers: myHeaders,
            // On ajoute les données, encodée en JSON, dans le corps de la requête
        };


        fetch(app.baseUrl + "v1/argonautes", fetchOptions)
            .then(
                function (response) {
                    return response.json();
                }
            )
            .then(
                function (name) {

                    console.log(name);
                    let text = name.message.data.name;
                    let ul = document.querySelector('.member-list')

                    let newLi = document.createElement('li');
                    debugger
                    console.log(name.message);

                    newLi.innerHTML = text
                    newLi.className = 'member-item'

                    ul.appendChild(newLi);


                    //on demande à charger nos tâches que lorsqu'on a reçu nos catégories ! 
                    app.loadTasks();

                    //cette fonction sera appelée quand les résultats seront arrivés
                    app.updateCategoriesSelects(categories);
                }
            );
    },



    handleInsertName: function (evt) {

        evt.preventDefault();
        let selectedCategory = evt.currentTarget
        console.log(selectedCategory);

        let input = document.querySelector(".new-member-form input[name='name']");
        let newName = input.value;
        console.log(newName);;

        data = {
            name: newName
        };

        let myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");

        // On consomme l'API pour ajouter en DB
        let fetchOptions = {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            // On ajoute les headers dans les options
            headers: myHeaders,
            // On ajoute les données, encodée en JSON, dans le corps de la requête
            body: JSON.stringify(data)
        };


        fetch(app.baseUrl + 'v1/insert', fetchOptions)
            .then(
                function (response) {

                    console.log(response.body);

                    if (response.status == 200) {
                        alert('Nom ajouté !');

                        return response.json()
                    } else {
                        alert('L\'ajout du nom a échoué');
                        input.value = ""
                        input.focus();
                        app.listenForEvents();
                    }
                }
            )
            .then(
                function (name) {
                    let ul = document.querySelector('.member-list')

                    let newLi = document.createElement('li');
                    debugger
                    console.log(name.message);
                    text = name.message.data.name;
                    newLi.innerHTML = text

                    ul.appendChild(newLi);

                    input.value = ""
                    input.focus();
                    app.listenForEvents();
                })
    },




}
app.init();