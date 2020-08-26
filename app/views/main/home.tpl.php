<!-- Main section -->
<main>

  <!-- New member form -->
  <h2 class="member-title">Ajouter un(e) Argonaute</h2>
  <form class="new-member-form" method="POST">
    <label for="name">Nom de l&apos;Argonaute</label>
    <input id="name" name="name" type="text" placeholder="Charalampos" />
    <button type="submit" name='submit' class="submit">Envoyer</button>
  </form>

  <!-- Member list -->
  <h2>Membres de l'équipage</h2>">
    <ul class="member-list">
      <?php foreach ($argonautes as $argonaute) : ?>
        <li class="member-item"><?= $argonaute->getName() ?> </li>
      <?php endforeach ?>
    </ul>
</main>


<script>
  let app = {



    //on définit ici nos propriétés réutilisables dans notre code
    fetchOptions: {
      method: 'POST',
      mode: 'cors',
      cache: 'no-cache',
    },

    baseUrl: "http://127.0.0.1:8000/api/",



    //méthode permettant d'initialiser notre application
    init: function() {
      //pour être sûr que tout marche bien
      console.log("coucou depuis l'init");


      //met en place toutes nos mises sous écoute
      app.listenForEvents();
    },

    listenForEvents: function() {
      let FormGroupCat = document.querySelector(".submit");
      FormGroupCat.addEventListener("click", app.handleInsertName);
    },





    handleInsertName: function(evt) {

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

      console.log(app.fetchOptions);

      fetch(app.baseUrl + 'insert', fetchOptions)
        .then(
          function(response) {

            console.log(response.body);

            if (response.status == 200) {
              alert('Nom ajouté !');
              console.log('nom ajouté')
              return response.json()
            } else {
              alert('L\'ajout du nom a échoué');
            }
          }
        )
        .then(
          function(name) {
            let ul = document.querySelector('.member-list')

            let newLi = document.createElement('li');
            newLi.textContent = newName
            ul.appendChild(newLi);

            input.value = ""
            input.focus();
            app.listenForEvents();
          })
    },

  }
  app.init();
</script>